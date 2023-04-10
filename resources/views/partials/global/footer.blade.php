<?php
$version = App\Support\System\VersionHelper::getVersionString();
$color = config('services.halotdotapi.disabled') ? 'is-danger' : 'is-warning';
?>
<footer class="footer mt-auto">
    <div class="content has-text-centered">
        @if (config('services.halotdotapi.warning_message'))
            <div class="notification {{ $color }}">
                {!! config('services.halotdotapi.warning_message') !!}
            </div>
        @endif
        <p>
            <strong>Leaf</strong>
            @if ($version)
                <a
                    href="https://github.com/iBotPeaches/LeafApp_Infinite/releases/tag/{{ $version }}"
                    target="_blank"
                    rel="nofollow"
                >
                    {{ $version }}
                </a>
            @endif
            by <a
                target="_blank"
                href="https://twitter.com/iBotPeaches"
                rel="noreferrer"
            >iBotPeaches</a>
            (<a
                target="_blank"
                href="https://connortumbleson.com"
                rel="author"
            >Connor</a>).

            <span class="is-hidden-mobile">
                <span class="has-tooltip-arrow" data-tooltip="100% Test Coverage!">Source code</span> is at
                <a
                    target="_blank"
                    href="https://github.com/iBotPeaches/LeafApp_Infinite"
                    rel="noreferrer"
                >GitHub</a>.

                API via <a
                    target="_blank"
                    href="https://halo.api/"
                    rel="noreferrer"
                >HaloDotAPI ({{ config('services.halotdotapi.version') }})</a>.
            </span>
        </p>
    </div>
</footer>
