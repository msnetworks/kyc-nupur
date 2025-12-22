@if ($paginator->hasPages())
<nav>
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        @php
            $total = $paginator->lastPage();
            $current = $paginator->currentPage();
            $window = config('pagination.window_size', 10);
            $trailing = config('pagination.trailing', 2);
        @endphp

        @if ($total <= $window + $trailing + 2)
            {{-- Show all pages when total is small --}}
            @for ($i = 1; $i <= $total; $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}" aria-current="page">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        @else
            @php
                $start = $current;
                $end = min($total, $current + $window - 1);
            @endphp

            {{-- Show first page and ellipsis if window does not start at 1 --}}
            @if ($start > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($start > 2)
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">&hellip;</span></li>
                @endif
            @endif

            {{-- Window pages (current .. end) --}}
            @for ($i = $start; $i <= $end; $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Show trailing pages if needed --}}
            @if ($end < $total - $trailing)
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&hellip;</span></li>
                @php $startLast = max($end+1, $total - $trailing + 1); @endphp
                @for ($i = $startLast; $i <= $total; $i++)
                    <li class="page-item {{ $i == $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
            @elseif ($end < $total)
                {{-- show remaining pages until total --}}
                @for ($i = $end+1; $i <= $total; $i++)
                    <li class="page-item {{ $i == $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
            @endif
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
</nav>
@endif