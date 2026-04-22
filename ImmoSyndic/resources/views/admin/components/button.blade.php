@props(['variant' => 'primary', 'size' => 'md', 'icon' => null])

@php
    $baseStyles = "inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent disabled:opacity-50 disabled:pointer-events-none transition-colors shadow-sm";
    
    $variants = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700',
        'secondary' => 'bg-white text-gray-800 border-gray-200 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
        'ghost' => 'bg-transparent text-gray-800 hover:bg-gray-100 border-none shadow-none dark:text-white dark:hover:bg-neutral-700',
    ];

    $sizes = [
        'sm' => 'py-1.5 px-2.5 text-xs',
        'md' => 'py-2 px-3',
        'lg' => 'py-3 px-4 text-base',
    ];

    $classes = "{$baseStyles} {$variants[$variant]} {$sizes[$size]}";
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i data-lucide="{{ $icon }}" class="size-4"></i>
    @endif
    {{ $slot }}
</button>
