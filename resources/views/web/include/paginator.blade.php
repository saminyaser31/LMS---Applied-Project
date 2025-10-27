<ul class="rbt-pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="disabled" aria-disabled="true"><span><i class="feather-chevron-left"></i></span></li>
    @else
        <li><a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous"><i class="feather-chevron-left"></i></a></li>
    @endif

    {{-- Pagination Links --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="active"><a href="#">{{ $page }}</a></li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <li><a href="{{ $paginator->nextPageUrl() }}" aria-label="Next"><i class="feather-chevron-right"></i></a></li>
    @else
        <li class="disabled" aria-disabled="true"><span><i class="feather-chevron-right"></i></span></li>
    @endif
</ul>
