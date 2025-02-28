<?php
    $color = config('services.dotapi.disabled')
        ? 'is-danger'
        : (is_null(config('services.dotapi.warning_message')) ? 'is-success' : 'is-warning');
?>
<nav class="navbar is-fixed-top {{ $color }}" role="navigation" aria-label="main navigation">
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
            @if (Auth::user() && Auth::user()->player)
                <a href="{{ route('player', Auth::user()->player) }}" class="navbar-item">
                    <i class="fa fa-user"></i>&nbsp;{{ Auth::user()->player->gamertag }}
                </a>
            @endif
            <a href="{{ route('championships') }}" class="navbar-item">
                HCS
            </a>
            <a href="{{ route('overviews') }}" class="navbar-item">
                Overviews
            </a>
            <a href="{{ route('topTenLeaderboards') }}" class="navbar-item">
                Top Ten
            </a>
            <a href="{{ route('medalLeaderboards') }}" class="navbar-item">
                Medals
            </a>
            <a href="{{ route('playlist') }}" class="navbar-item">
                Playlists
            </a>
            <a href="{{ route('scrims') }}" class="navbar-item">
                Scrims
            </a>
            <a href="{{ route('about') }}" class="navbar-item">
                About
            </a>
        </div>

        <div class="navbar-end">
            <div class="navbar-item m-0 py-0">
                @if (!request()->routeIs('home'))
                    <div class="navbar-item px-2">
                        <livewire:add-gamer-form :isNav="true" />
                    </div>
                @endif

                <div class="buttons">
                    @guest
                        <a href="{{ route('googleRedirect') }}" class="button is-danger">
                            <strong>Google</strong>
                        </a>
                    @endguest
                    @auth
                        <a href="{{ route('logout') }}" class="button is-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endauth
                    <a target="_blank" href="https://www.buymeacoffee.com/iBotPeaches" rel="nofollow" class="button is-warning has-tooltip-bottom" data-tooltip="Donate">
                        <i class="fas fa-coffee"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
