@props([
    'route' => '#',
    'search' => '',
])

<form method="GET" action="{{ $route }}" class="flex flex-wrap items-center space-x-2 sm:space-x-3 md:space-x-4">
    <label for="entries" class="text-xs sm:text-sm">Show:</label>
    @if ($search)
        <input type="hidden" name="search" value="{{ $search }}">
    @endif
    <select
        class="w-auto min-w-[60px] sm:min-w-[80px] md:w-24 px-2 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"
        name="entries" id="entries" onchange="this.form.submit()">
        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
    </select>
    <label for="entries" class="text-xs sm:text-sm">entries</label>
</form>