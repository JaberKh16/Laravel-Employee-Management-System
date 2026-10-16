 <div class="relative" id="downloadWrap">
        <button type="button"
                id="downloadToggle"
                class="toolbar-btn rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            <i class="ri-download-2-line text-lg text-indigo-500"></i>
            <span class="hidden md:inline">Download</span>
            <i class="ri-arrow-down-s-line text-base text-gray-400"></i>
        </button>

        <div id="downloadMenu"
             class="absolute right-0 z-20 mt-2 hidden w-56 origin-top-right rounded-xl border border-gray-200 bg-white p-1.5 shadow-xl dark:border-gray-700 dark:bg-gray-800">

            @foreach ($downloadOptions as $dl)
                @if ($dl['format'] === 'print')
                    {{-- Print uses JS --}}
                    <button type="button"
                            onclick="window.print()"
                            class="no-loader flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="{{ $dl['icon'] }} {{ $dl['color'] }} w-4 text-center text-lg"></i>
                        <span>{{ $dl['label'] }}</span>
                    </button>
                @else
                    {{-- Download links --}}
                    <a href="{{ route('users.export', array_merge(request()->query(), ['format' => $dl['format']])) }}"
                       data-download
                       data-loading-text="Preparing {{ strtoupper($dl['format']) }}…"
                       class="download-item no-loader flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="{{ $dl['icon'] }} {{ $dl['color'] }} w-4 text-center text-lg"></i>
                        <span class="download-label">{{ $dl['label'] }}</span>
                        <span class="spinner-btn ml-auto hidden">
                            <svg class="h-4 w-4 animate-spin text-indigo-600" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/>
                                <path fill="currentColor" class="opacity-90"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>