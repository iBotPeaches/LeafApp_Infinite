@if ($paginator->hasPages())
    <nav class="pagination is-centered">
        @if ($paginator->onFirstPage())
            <a class="pagination-previous" disabled>Previous</a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-previous">Previous</a>
        @endif

        @if ($paginator->hasMorePages())
            <a class="pagination-next" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
        @else
            <a class="pagination-next" disabled>Next page</a>
        @endif

        @if (isset($elements))
            <ul class="pagination-list">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="pagination-ellipsis"><span>{{ $element }}</span></span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li><a class="pagination-link is-current">{{ $page }}</a></li>
                            @else
                                <li><a href="{{ $url }}" class="pagination-link">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        @endif
    </nav>
    <br />
@endif
