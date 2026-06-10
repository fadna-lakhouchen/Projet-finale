<!-- Minimal Footer -->
<footer class="bg-white dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2 group cursor-pointer">
                <img src="{{ asset('logo.png') }}" alt="Logo ImmoSyndic" class="h-8 w-auto object-contain group-hover:rotate-12 transition-transform">
                <span class="font-heading font-extrabold text-xl text-gray-900 dark:text-white">Immo<span class="text-brand-900">Syndic</span></span>
            </div>
            <div class="flex gap-6 text-sm font-semibold text-gray-500 dark:text-gray-400">
                <a href="#" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors">Mentions légales</a>
                <a href="#" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors">Confidentialité</a>
                <a href="#" class="hover:text-teal-600 dark:hover:text-teal-400 transition-colors">Contact</a>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-800 text-center text-sm text-gray-400 dark:text-gray-600">
            &copy; {{ date('Y') }} ImmoSyndic. Tous droits réservés. Conçu pour le PFA.
        </div>
    </div>
</footer>

<!-- Initialize Icons -->
<script>
    lucide.createIcons();
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
</script>
