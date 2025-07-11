<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingManager extends Component
{
    public $property_id;    // ID du bien immobilier sélectionné
    public $start_date;     // Date de début de réservation
    public $end_date;       // Date de fin de réservation
    public $properties;     // Liste des propriétés
    public $existingBookings = [];

    public function updatedPropertyId()
    {
        $this->loadBookings();
    }

    public function loadBookings()
    {
        if ($this->property_id) {
            $this->existingBookings = Booking::where('property_id', $this->property_id)
                ->orderBy('start_date')
                ->get();
        } else {
            $this->existingBookings = [];
        }
    }

    public function mount()
    {
        $this->properties = Property::all();
        $this->loadBookings();
    }

    public function render()
    {
        // On pourrait recharger la liste à chaque rendu si nécessaire
        // $this->properties = Property::all();

        return view('livewire.booking-manager');
    }

    public function book()
    {
        $this->validate([
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Vérifier les conflits de réservation
        $conflict = Booking::where('property_id', $this->property_id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                      ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                      ->orWhere(function ($query) {
                          $query->where('start_date', '<=', $this->start_date)
                                ->where('end_date', '>=', $this->end_date);
                      });
            })
            ->exists();

        if ($conflict) {
            $this->addError('start_date', 'Des réservations existent déjà sur cette période.');
            return;
        }

        Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $this->property_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        // Réinitialiser les champs après réservation
        $this->property_id = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->existingBookings = [];

        session()->flash('success', 'Réservation créée avec succès.');

        // Dispatch d'un événement Livewire pour notification JS
        $this->dispatch('bookingCreated');
    }
}
