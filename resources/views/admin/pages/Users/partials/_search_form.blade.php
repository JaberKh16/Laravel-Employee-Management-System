<form action="{{ route('users.index') }}"
          method="GET"
          id="mainSearchForm"
          class="toolbar-row">

        @foreach (['name','email','date','dob','status','updated_from','updated_to','criteria'] as $param)
            @if (request($param))
                <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
            @endif
        @endforeach

        {{-- Search input --}}
        <div class="relative flex-1 min-w-[200px]">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="ri-search-line text-lg"></i>
            </span>
            <input type="search"
                   name="search"
                   id="search"
                   value="{{ request('search') }}"
                   placeholder="Search users…"
                   class="toolbar-input block w-full rounded-xl border border-gray-300 bg-white pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
        </div>

        {{-- Search button --}}
        @include('components.loading.button', [
            'id'      => 'searchBtn',
            'label'   => 'Search',
            'loading' => 'Searching…',
            'type'    => 'submit',
            'variant' => 'primary',
            'icon'    => 'ri-search-line',
            'class'   => 'toolbar-btn min-w-[110px]',
        ])

        @if (request()->hasAny(['search','name','email','date','dob','status','updated_from','updated_to','criteria']))
            <a href="{{ route('users.index') }}"
               class="toolbar-btn rounded-xl border border-gray-300 bg-white px-4 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="ri-close-line text-lg"></i>
                <span class="hidden sm:inline">Clear</span>
            </a>
        @endif
    </form>