<nav class="navbar is-fixed-top is-success" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="{{ url('/') }}">
            Leaf
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbar">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbar" class="navbar-menu">
        <div class="navbar-start">
            <a href="{{ route('championships') }}" class="navbar-item">
                HCS Open
            </a>
            <a href="{{ route('about') }}" class="navbar-item">
                About
            </a>
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a target="_blank" href="https://github.com/iBotPeaches/LeafApp_Infinite" class="button is-info">
                        <strong>GitHub</strong>
                    </a>
                    <a target="_blank" href="https://www.buymeacoffee.com/iBotPeaches" class="button is-warning">
                        <i class="fas fa-coffee"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
