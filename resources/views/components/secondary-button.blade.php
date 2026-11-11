<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-surface-lowest border border-border-subtle rounded-lg font-semibold text-xs text-on-surface uppercase tracking-wider shadow-xs hover:bg-surface-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
