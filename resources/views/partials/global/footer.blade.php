<?php
$version = App\Support\System\VersionHelper::getVersionString();
?>
<footer class="footer mt-auto">
    <div class="content has-text-centered">
        @if (config('services.autocode.warning_message'))
            <div class="notification is-warning">
                {{ config('services.autocode.warning_message') }}
            </div>
        @endif
        <p>
            <strong>Leaf</strong>
            @if ($version)
                <a
                    href="https://github.com/iBotPeaches/LeafApp_Infinite/releases/tag/{{ $version }}"
                    target="_blank"
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
                The <span class="has-tooltip-arrow" data-tooltip="100% Test Coverage!">source code</span> is at
                <a
                    target="_blank"
                    href="https://github.com/iBotPeaches/LeafApp_Infinite"
                    rel="noreferrer"
                >GitHub</a>.

                API Data from <a
                    target="_blank"
                    href="https://autocode.com/lib/halo/"
                    rel="noreferrer"
                >HaloDotAPI (Autocode)</a>.
            </span>
        </p>
    </div>
</footer>
