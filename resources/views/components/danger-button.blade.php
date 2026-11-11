<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-error border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:bg-error/90 focus:outline-none focus:ring-2 focus:ring-error focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
