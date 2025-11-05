<div class="mt-4 flex justify-end items-center">
    {{-- Pagination Links --}}
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="inline-flex -space-x-px">
            {{-- First Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-l-md">
                        <<
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->url(1) }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:text-gray-500">
                        <<
                    </a>
                </li>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                         <
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:text-gray-500 rounded-md">
                         <
                    </a>
                </li>
            @endif

            {{-- Page Number Links --}}
            @php
                $startPage = max(1, $paginator->currentPage() - 1);
                $endPage = min($paginator->lastPage(), $paginator->currentPage() + 1);
            @endphp

            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $paginator->currentPage())
                    <li>
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-gray-300 cursor-default rounded-md">
                            {{ $page }}
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($page) }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:text-gray-500">
                            {{ $page }}
                        </a>
                    </li>
                @endif
            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:text-gray-500 rounded-md">
                        >
                    </a>
                </li>
            @else
                <li>
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                        >
                    </span>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->onLastPage())
                <li>
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-r-md">
                        >>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:text-gray-500">
                        >>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
