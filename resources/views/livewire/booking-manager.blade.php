<div>
    <h2 class="text-2xl font-bold mb-4">Réservez un bien immobilier</h2>

    {{-- Message flash succès --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @foreach ($properties as $property)
            <div
                class="border rounded-lg p-4 shadow hover:shadow-lg transition duration-200 cursor-pointer
                {{ $property_id == $property->id ? 'border-primary bg-white' : 'border-gray-300 bg-gray-50' }}"
                wire:click="$set('property_id', {{ $property->id }})"
            >
                <h3 class="text-xl font-semibold">{{ $property->name }}</h3>
                <p class="mt-2 text-gray-600">{{ \Illuminate\Support\Str::limit($property->description, 100) }}</p>
                <p class="mt-4 font-bold text-primary">{{ number_format($property->price_per_night, 2) }} € / nuit</p>
            </div>
        @endforeach
    </div>

    <div class="mb-4">
        <label for="start_date" class="block font-medium text-gray-700">Date de début</label>
        <input type="date" id="start_date" wire:model="start_date" class="border rounded p-2 w-full" />
        @error('start_date')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="end_date" class="block font-medium text-gray-700">Date de fin</label>
        <input type="date" id="end_date" wire:model="end_date" class="border rounded p-2 w-full" />
        @error('end_date')
            <span class="text-red-600 text-sm">{{ $message }}</span>
        @enderror
    </div>

    {{-- Affichage erreur sur la propriété sélectionnée --}}
    @error('property_id')
        <div class="mb-4 text-red-600 text-sm">
            {{ $message }}
        </div>
    @enderror

    <button
        wire:click="book"
        class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark disabled:opacity-50"
        @if(!$property_id || !$start_date || !$end_date) disabled @endif
    >
        Réserver
    </button>

    {{-- Affichage des réservations existantes pour la propriété sélectionnée --}}
    @if($property_id && count($existingBookings) > 0)
        <div class="mt-8">
            <h4 class="font-semibold mb-2">Réservations existantes pour ce bien :</h4>
            <ul class="space-y-2">
                @foreach($existingBookings as $booking)
                    <li class="p-2 bg-gray-100 rounded flex justify-between items-center">
                        <span>
                            Du <strong>{{ $booking->start_date }}</strong> au <strong>{{ $booking->end_date }}</strong>
                        </span>
                        <span class="text-xs text-gray-500">Réservé par utilisateur #{{ $booking->user_id }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @elseif($property_id)
        <div class="mt-8 text-gray-500">Aucune réservation existante pour ce bien.</div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('bookingCreated', () => {
            // Affichage d'une notification toast simple
            const toast = document.createElement('div');
            toast.textContent = 'Réservation créée avec succès !';
            toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        });
    });
</script>
