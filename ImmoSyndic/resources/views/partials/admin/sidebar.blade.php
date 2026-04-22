<!-- Sidebar Toggle -->
<div class="lg:hidden sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 md:px-8 py-2 dark:bg-neutral-800 dark:border-neutral-700">
  <div class="flex items-center py-2">
    <!-- Navigation Toggle -->
    <button type="button" class="size-8 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none focus:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:text-neutral-200 dark:hover:text-neutral-500" data-hs-overlay="#application-sidebar" aria-controls="application-sidebar" aria-label="Toggle navigation">
      <span class="sr-only">Toggle Navigation</span>
      <i data-lucide="menu" class="size-4"></i>
    </button>
    <!-- End Navigation Toggle -->

    <!-- Breadcrumb -->
    <ol class="ms-3 flex items-center whitespace-nowrap">
      <li class="flex items-center text-sm text-gray-800 dark:text-neutral-400">
        Application
        <i data-lucide="chevron-right" class="size-3 mx-2 text-gray-400"></i>
      </li>
      <li class="text-sm font-semibold text-gray-800 truncate dark:text-neutral-400" aria-current="page">
        Dashboard
      </li>
    </ol>
    <!-- End Breadcrumb -->
  </div>
</div>
<!-- End Sidebar Toggle -->

<!-- Sidebar -->
<div id="application-sidebar" class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 pt-7 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-800 dark:border-neutral-700">
  <div class="px-6 flex items-center gap-x-3 mb-8">
    <div class="size-8 bg-primary-600 rounded-lg flex items-center justify-center text-white shadow-md">
      <i data-lucide="building-2" class="size-5"></i>
    </div>
    <a class="flex-none text-xl font-bold dark:text-white" href="#" aria-label="Brand">ImmoSyndic</a>
  </div>

  <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
    <ul class="space-y-1.5">
      <li>
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 bg-primary-50 text-sm text-primary-700 font-medium rounded-lg dark:bg-primary-900/30 dark:text-primary-400" href="/admin/dashboard">
          <i data-lucide="layout-dashboard" class="size-4"></i>
          Dashboard
        </a>
      </li>

      <li>
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors" href="/admin/residents">
          <i data-lucide="users" class="size-4"></i>
          Gestion Résidents
        </a>
      </li>

      <li>
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors" href="/admin/syndics">
          <i data-lucide="user-cog" class="size-4"></i>
          Gestion Syndics
        </a>
      </li>

      <li>
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors" href="/admin/paiements">
          <i data-lucide="credit-card" class="size-4"></i>
          Paiements
        </a>
      </li>

      <li>
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors" href="/admin/signalements">
          <i data-lucide="alert-triangle" class="size-4"></i>
          Signalements
          <span class="ms-auto inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-red-100 text-red-600">3</span>
        </a>
      </li>

      <li>
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors" href="/admin/documents">
          <i data-lucide="folder-archive" class="size-4"></i>
          Documents / Archives
        </a>
      </li>

      <li>
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors" href="/admin/rapports">
          <i data-lucide="pie-chart" class="size-4"></i>
          Rapports financiers
        </a>
      </li>

      <li class="mt-4 pt-4 border-t border-gray-200 dark:border-neutral-700">
        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 transition-colors" href="/admin/parametres">
          <i data-lucide="settings" class="size-4"></i>
          Paramètres
        </a>
      </li>
    </ul>
  </nav>
</div>
<!-- End Sidebar -->
