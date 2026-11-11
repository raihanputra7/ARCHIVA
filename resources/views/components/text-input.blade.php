@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-11 px-3.5 py-2.5 rounded-lg text-sm bg-surface-lowest text-on-surface placeholder:text-outline border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none disabled:opacity-50 disabled:bg-surface-container-low disabled:cursor-not-allowed transition-colors shadow-xs']) }}>
