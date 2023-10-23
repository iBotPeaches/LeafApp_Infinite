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
@if ($paginator->hasPages())
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        @if ($paginator->onFirstPage())
            <button class="pagination-previous" wire:key="paginator-{{ $paginator->getPageName() }}-dead-previous" disabled>Previous</button>
        @else
            <button class="pagination-previous" wire:key="paginator-{{ $paginator->getPageName() }}-previous" wire:click="previousPage" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled">Previous</button>
        @endif

        @if ($paginator->hasMorePages())
            <button class="pagination-next" wire:key="paginator-{{ $paginator->getPageName() }}-next" wire:click="nextPage" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled">Next page</button>
        @else
            <button class="pagination-next" wire:key="paginator-{{ $paginator->getPageName() }}-dead-next" disabled>Next page</button>
        @endif
    </nav>
@endif
