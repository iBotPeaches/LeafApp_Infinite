<?php
$version = App\Support\System\VersionHelper::getVersionString();
?>
<footer class="footer mt-auto">
    <div class="content has-text-centered">
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
            by <a href="https://twitter.com/iBotPeaches">iBotPeaches</a>. The source code is at
            <a href="https://github.com/iBotPeaches/LeafApp_Infinite">GitHub</a>.

            API Data from <a href="https://autocode.com/lib/halo/">HaloDotAPI (Autocode)</a>
        </p>
    </div>
</footer>
