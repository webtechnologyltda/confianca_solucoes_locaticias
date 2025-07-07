@if (Auth::check() && Auth::user()->isSuperAdmin())
    <a href="{{ filament()->getUrl() }}/horizon"
        class="fi-icon-btn relative flex items-center justify-center border dark:border dark:border-gray-400 rounded-lg
        bg-gray-50 dark:bg-gray-800 transition duration-75 focus-visible:ring-2 -m-1.5 ml-2 h-7 w-7 text-gray-400
        hover:text-gray-600 focus-visible:ring-primary-600 dark:text-gray-500 dark:hover:text-gray-400
        dark:focus-visible:ring-primary-500 fi-color-gray"
        title="Monitoramento de Jobs" target="_blank">
        <x-tabler-heart-rate-monitor class="w-4 h-4 fi-icon-btn-icon" />
    </a>
@endif
