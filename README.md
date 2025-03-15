# Leaf (Infinite)
[![Laravel](https://github.com/iBotPeaches/LeafApp_Infinite/actions/workflows/laravel.yml/badge.svg)](https://github.com/iBotPeaches/LeafApp_Infinite/actions/workflows/laravel.yml)

_Another hobby stat site for another Halo game_

### Branches
 * `master` - Branch off it. Must be stable.

### Setup (Docker)
A docker-based infrastructure is available for development. If you wish to run directly on host, see [Setup](#setup) below.

1. `cp .env.example .env`
1. Set the database hostname in `.env` like `DB_HOST=leaf-db`
1. `HOST_UID=$(id -u) docker compose up -d`
1. `docker exec -it leaf-php composer install`
1. `docker exec -it leaf-php npm ci`
1. `docker exec -it leaf-php php artisan key:generate`
1. `docker exec -it leaf-php php artisan migrate`
1. `docker exec -it leaf-php npm run build`

* Nginx listens at your local IP address, port 8822, e.g. http://localhost:8822
* MariaDB container's port 3306 is mapped to the host.
* Xdebug is configured to make connections to port 9003 on the host. Path mappings should be set up so the root of the project is mapped to `/var/www` on the server.

### Setup
1. PHP8.4
1. Node + NPM installed
1. MariaDB 11.4
1. [Composer](https://getcomposer.org/) installed.
1. `cp .env.example .env`
1. `composer install`
1. `npm ci`
1. `php artisan key:generate`
1. `php artisan migrate`
1. `npm run build`
1. `php artisan serve`

### Contributions
 * Code must pass phpcs. (`./vendor/bin/phpcs`)
 * Code must pass phpstan. (`./vendor/bin/phpstan analyse`)
 * Code must have 100% test coverage. (`composer coverage`)

### Configuration

#### DotAPI
Used for the API for Infinite information, an amazing service that proxies some internal HaloWaypoint/Live calls.

 * `DOTAPI_DISABLED` - Defaults to false, if toggled disables all Autocode API calls.
 * `DOTAPI_XUID_DISABLED` - Defaults to false, if toggled disables XUID resolution.
 * `DOTAPI_KEY` - The API key given [dotapi.gg](https://dotapi.gg)
 * `DOTAPI_DOMAIN` - The base domain for Autocode
 * `DOTAPI_VERSION` - The version of the Autocode to use
 * `DOTAPI_COOLDOWN` - The amount of time to wait between refreshing profiles automatically.
 * `DOTAPI_CURRENT_SEASON` - Current Halo Infinite Season
 * `DOTAPI_CURRENT_SEASON_VERSION` - Current Halo Infinite Season Version
 * `DOTAPI_WARNING_MESSAGE` - If populated, puts site into warning mode.

#### FaceIt
Used for HCS features for listing championships.

 * `FACEIT_KEY` - The API key given from [Developer Portal](https://developers.faceit.com)
 * `FACEIT_DOMAIN` - The base domain for FaceIt
 * `FACEIT_WEBHOOK_SECRET` - The exchanged secret for validating Webhook messages.

Additionally, register webhooks for the following events:

 * `match_status_finished`
 * `championship_finished`

Finally, create a security header called `X-Cat-Dog` and set it to a random value that you add to env.

#### Google OAuth
Used for marking an account as "you" and supporting making "scrims".

 * `GOOGLE_CLIENT_ID` - The clientId
 * `GOOGLE_CLIENT_SECRET` - The clientSecret
 * `GOOGLE_REDIRECT` - The URL to redirect to

#### Tinify
Used for optimizing images that come from backdrops and emblems.
 * `IMAGE_OPTIMIZE_KEY` - The key for API use.
 * `IMAGE_DOMAIN` - The domain for API Service

### Dependencies

#### Node
1. Bulma - https://github.com/jgthms/bulma - MIT
1. FontAwesome - https://github.com/FortAwesome/Font-Awesome - Font Awesome Free License
1. Bulma-Dividers - https://github.com/CreativeBulma/bulma-divider - MIT
1. Bulma-Tooltip - https://github.com/CreativeBulma/bulma-tooltip - MIT
1. Bulma-Ribbon - https://github.com/Wikiki/bulma-ribbon - MIT

#### PHP
1. PHP CodeSniffer - https://github.com/squizlabs/PHP_CodeSniffer - BSD-3-Clause 
1. Larastan - https://github.com/nunomaduro/larastan - MIT
1. Guzzle - https://github.com/guzzle/guzzle - MIT
1. Livewire - https://github.com/livewire/livewire - MIT
1. Enum - https://github.com/BenSampo/laravel-enum - MIT
1. DBAL - https://github.com/doctrine/dbal - MIT
1. SEOTools - https://github.com/artesaos/seotools - MIT
1. TheLeague/CSV - https://github.com/thephpleague/csv - MIT
1. Socialite - https://github.com/laravel/socialite - MIT
1. Sitemap - https://github.com/spatie/laravel-sitemap - MIT
1. Horizon - https://github.com/laravel/horizon - MIT
1. Markdown - https://github.com/spatie/laravel-markdown - MIT
1. Sentry - https://github.com/getsentry/sentry-laravel - MIT
1. Crawler Detect - https://github.com/JayBizzle/Laravel-Crawler-Detect - MIT
