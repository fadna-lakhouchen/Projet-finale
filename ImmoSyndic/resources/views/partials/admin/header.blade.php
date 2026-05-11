<!-- Header -->
<header class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b border-gray-200 text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-neutral-800 dark:border-neutral-700">
    <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6" aria-label="Global">
        <div class="me-5 lg:me-0 lg:hidden">
            <a class="flex-none text-xl font-semibold dark:text-white" href="#" aria-label="Brand">ImmoSyndic</a>
        </div>

        <div class="w-full flex items-center justify-end ms-auto sm:justify-between sm:gap-x-3">
            <div class="hidden sm:flex items-center gap-x-3">
                <h1 class="text-lg font-semibold text-gray-800 dark:text-white">Bonjour, Mohamed Rifi</h1>
                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                    <span class="size-1.5 inline-block bg-red-800 rounded-full dark:bg-red-500"></span> Super Admin
                </span>
            </div>

            <!-- Controls -->
            <div class="flex flex-row items-center justify-end gap-2">
                <!-- Notifications -->
                <button type="button" class="size-[38px] relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-700">
                    <i data-lucide="bell" class="size-5"></i>
                    <span class="absolute top-0 end-0 inline-flex items-center size-2 rounded-full bg-red-600 border-2 border-white dark:border-neutral-800"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="hs-dropdown [--placement:bottom-right] relative inline-flex" x-data="{ open: false }">
                    <button id="hs-dropdown-account" type="button" class="size-[38px] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 focus:outline-none disabled:opacity-50 disabled:pointer-events-none dark:text-white" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                        <img class="shrink-0 size-[38px] rounded-full ring-2 ring-white dark:ring-neutral-800" src="https://ui-avatars.com/api/?name=Mohamed+Rifi&background=3b66f5&color=fff" alt="User Avatar">
                    </button>

                    <div class="hs-dropdown-menu transition-[opacity,margin] duration opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700" aria-labelledby="hs-dropdown-account">
                        <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg mb-2 dark:bg-neutral-700">
                            <p class="text-sm text-gray-500 dark:text-neutral-400">Connecté en tant que</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">mohamed.rifi@email.com</p>
                        </div>
                        <div class="p-1.5 space-y-0.5">
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#">
                                <i data-lucide="user" class="size-4"></i>
                                Mon Profil
                            </a>
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#">
                                <i data-lucide="settings" class="size-4"></i>
                                Paramètres
                            </a>
                            <hr class="my-2 border-gray-200 dark:border-neutral-700">
                            <form method="POST" action="#">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:text-red-500 dark:hover:bg-red-900/10">
                                    <i data-lucide="log-out" class="size-4"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Profile Dropdown -->
            </div>
        </div>
    </nav>
</header>
<!-- End Header -->
