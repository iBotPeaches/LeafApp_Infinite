@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp
<div>
    @if ($paginator->hasPages())
        <nav class="pagination is-centered">
            @if ($paginator->onFirstPage())
                <a class="pagination-previous" wire:key="paginator-{{ $paginator->getPageName() }}-dead-previous" disabled>Previous</a>
            @else
                <a class="pagination-previous" wire:key="paginator-{{ $paginator->getPageName() }}-previous" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" rel="prev" aria-label="@lang('pagination.previous')">Previous</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="pagination-next" wire:key="paginator-{{ $paginator->getPageName() }}-next" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" rel="next" aria-label="@lang('pagination.next')">Next</a>
            @else
                <a class="pagination-next" wire:key="paginator-{{ $paginator->getPageName() }}-dead-next" disabled>Next page</a>
            @endif

            <ul class="pagination-list">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="pagination-ellipsis"><span>{{ $element }}</span></span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li wire:key="paginator-{{ $paginator->getPageName() }}-dead-page-{{ $page }}"><span class="pagination-link is-current" aria-current="page">{{ $page }}</span></li>
                            @else
                                <li wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}"><a class="pagination-link" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </nav>
    @endif
</div>
