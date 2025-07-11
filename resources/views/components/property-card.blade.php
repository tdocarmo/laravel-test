<div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm hover:shadow-md transition duration-200">
    <h2 class="text-xl font-bold text-black">{{ $property->name }}</h2>
    <p class="mt-2 text-gray-600">{{ Str::limit($property->description, 100) }}</p>
    <p class="mt-4 font-bold text-primary">{{ number_format($property->price_per_night, 2) }} € / nuit</p>
    <x-button class="mt-4">Réserver</x-button>
</div>
