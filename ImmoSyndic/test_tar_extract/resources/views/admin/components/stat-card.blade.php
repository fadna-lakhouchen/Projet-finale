@props(['title', 'value', 'trend' => null, 'trendUp' => true, 'icon' => 'activity'])

<div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
  <div class="p-4 md:p-5">
    <div class="flex items-center gap-x-2">
      <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
        {{ $title }}
      </p>
      @if($icon)
      <div class="ms-auto">
         <i data-lucide="{{ $icon }}" class="size-4 text-gray-400"></i>
      </div>
      @endif
    </div>

    <div class="mt-1 flex items-center gap-x-2">
      <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
        {{ $value }}
      </h3>
      @if($trend)
        <span class="inline-flex items-center gap-x-1 py-0.5 px-2 justify-center rounded-full {{ $trendUp ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }} text-xs font-medium">
          <i data-lucide="{{ $trendUp ? 'trending-up' : 'trending-down' }}" class="size-3"></i>
          {{ $trend }}
        </span>
      @endif
    </div>
  </div>
</div>
