@props(['headers' => []])

<div class="flex flex-col">
  <div class="-m-1.5 overflow-x-auto">
    <div class="p-1.5 min-w-full inline-block align-middle">
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
          <thead class="bg-gray-50 dark:bg-neutral-800">
            <tr>
              @foreach($headers as $header)
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                {{ $header }}
              </th>
              @endforeach
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
            {{ $slot }}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
