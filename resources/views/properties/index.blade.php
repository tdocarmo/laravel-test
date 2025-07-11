<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-primary">Nos biens disponibles</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($properties as $property)
                <x-property-card :property="$property" />
            @endforeach
        </div>
    </div>
</x-app-layout>
