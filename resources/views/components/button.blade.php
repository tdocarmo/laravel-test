<button
    {{ $attributes->merge(['class' => 'px-4 py-2 bg-primary hover:bg-teal-700 text-white rounded-lg font-semibold transition duration-200']) }}
>
    {{ $slot }}
</button>
