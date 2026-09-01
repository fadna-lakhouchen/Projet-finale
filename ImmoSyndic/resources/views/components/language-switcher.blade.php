<div x-data="{ open: false }" class="relative inline-block text-left">
    <button @click="open = !open" @click.outside="open = false" type="button" class="inline-flex items-center gap-x-2 py-2 px-3 text-sm font-semibold rounded-xl border border-gray-200/50 bg-white/40 hover:bg-white text-gray-800 shadow-sm dark:text-white dark:bg-neutral-800/40 dark:border-neutral-700/50 dark:hover:bg-neutral-800 transition-all">
        <i data-lucide="globe" class="size-4 text-gray-550 dark:text-neutral-400"></i>
        <span>
            @if(app()->getLocale() === 'ar')
                العربية
            @elseif(app()->getLocale() === 'en')
                English
            @else
                Français
            @endif
        </span>
        <i data-lucide="chevron-down" class="size-3 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-[60] mt-2 w-32 rounded-xl bg-white shadow-xl border border-gray-150 p-1 dark:bg-neutral-900 dark:border-neutral-800 focus:outline-none {{ app()->getLocale() === 'ar' ? 'left-0 origin-top-left' : 'right-0 origin-top-right' }}"
         style="display: none;">
         
         <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors {{ app()->getLocale() === 'ar' ? 'bg-primary-50 dark:bg-neutral-850 font-semibold text-primary-600 dark:text-white' : '' }}">
             <span>العربية</span>
         </a>
         <a href="{{ route('lang.switch', 'fr') }}" class="flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors {{ app()->getLocale() === 'fr' ? 'bg-primary-50 dark:bg-neutral-850 font-semibold text-primary-600 dark:text-white' : '' }}">
             <span>Français</span>
         </a>
         <a href="{{ route('lang.switch', 'en') }}" class="flex items-center py-2 px-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 dark:text-neutral-350 dark:hover:bg-neutral-800 transition-colors {{ app()->getLocale() === 'en' ? 'bg-primary-50 dark:bg-neutral-850 font-semibold text-primary-600 dark:text-white' : '' }}">
             <span>English</span>
         </a>
    </div>
</div>
