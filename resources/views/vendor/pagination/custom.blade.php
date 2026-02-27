<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
    
    {{-- Mobile View --}}
    <div class="flex justify-between flex-1 sm:hidden">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                {!! __('< Sebelumnya') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                {!! __('< Sebelumnya') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                {!! __('Selanjutnya >') !!}
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md">
                {!! __('Selanjutnya >') !!}
            </span>
        @endif
    </div>

    {{-- Desktop View --}}
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-xs text-gray-400">
                Menampilkan
                @if ($paginator->firstItem())
                    <span>{{ $paginator->firstItem() }}</span>
                    sampai
                    <span>{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <span>{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>

        <div>
            <span class="relative z-0 inline-flex rounded-md gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-md">
                            &lt; Sebelumnya
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-md hover:bg-gray-50" aria-label="{{ __('pagination.previous') }}">
                        &lt; Sebelumnya
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-200 cursor-default rounded-md">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-[#3B5286] border border-[#3B5286] cursor-default rounded-md">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-md hover:bg-gray-50" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-md hover:bg-gray-50" aria-label="{{ __('pagination.next') }}">
                        Selanjutnya &gt;
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-md">
                            Selanjutnya &gt;
                        </span>
                    </span>
                @endif
            </span>
        </div>
    </div>
</nav>
