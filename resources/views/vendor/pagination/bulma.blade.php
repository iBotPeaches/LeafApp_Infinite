<div>
    @if ($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)

        <nav class="pagination is-centered">
            @if ($paginator->onFirstPage())
                <a class="pagination-previous" wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-dead-previous" disabled>Previous</a>
            @else
                <a class="pagination-previous" wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-previous" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="pagination-next" wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-next" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next" aria-label="@lang('pagination.next')">Next</a>
            @else
                <a class="pagination-next" wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-dead-next" disabled>Next page</a>
            @endif

            <ul class="pagination-list">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="pagination-ellipsis"><span>{{ $element }}</span></span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-dead-page-{{ $page }}"><span class="pagination-link is-current" aria-current="page">{{ $page }}</span></li>
                            @else
                                <li wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page-{{ $page }}"><a class="pagination-link" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </nav>
    @endif
</div>
