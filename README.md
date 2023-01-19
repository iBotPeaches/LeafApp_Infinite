# Leaf (Infinite)
[![Laravel](https://github.com/iBotPeaches/LeafApp_Infinite/actions/workflows/laravel.yml/badge.svg)](https://github.com/iBotPeaches/LeafApp_Infinite/actions/workflows/laravel.yml)

_Another hobby stat site for another Halo game_

### Branches
 * `master` - Branch off it. Must be stable.

### Setup (Docker)
A docker-based infrastructure is available for development. If you wish to run directly on host, see [Setup](#setup) below.

1. `cp .env.example .env`
1. Set the database hostname in `.env` like `DB_HOST=leaf-db`
1. `docker compose --env-file .env -p leaf -f docker/docker-compose.yml up -d`
1. `docker exec -it leaf-php composer install`
1. `docker exec -it leaf-php yarn install`
1. `docker exec -it leaf-php php artisan key:generate`
1. `docker exec -it leaf-php php artisan migrate`
1. `docker exec -it leaf-php yarn run dev`

### Setup
1. PHP8.1
1. Node + Yarn installed
1. MariaDB 10.4+
1. [Composer](https://getcomposer.org/) installed.
1. `cp .env.example .env`
1. `composer install`
1. `yarn install`
1. `php artisan key:generate`
1. `php artisan migrate`
1. `yarn run dev`
1. `php artisan serve`

### Contributions
 * Code must pass phpcs. (`./vendor/bin/phpcs`)
 * Code must pass phpstan. (`./vendor/bin/phpstan analyse`)
 * Code must have 100% test coverage. (`composer coverage`)

### Configuration

#### HaloDotApi (Autocode)
Used for the API for Infinite information, an amazing service that proxies some internal HaloWaypoint/Live calls.

 * `AUTOCODE_DISABLED` - Defaults to false, if toggled disables all Autocode API calls.
 * `AUTOCODE_KEY` - The API key given [Autocode](https://autocode.com/lib/halo/)
 * `AUTOCODE_DOMAIN` - The base domain for Autocode
 * `AUTOCODE_VERSION` - The version of the Autocode to use
 * `AUTOCODE_COOLDOWN` - The amount of time to wait between refreshing profiles automatically.
 * `AUTOCODE_CURRENT_SEASON` - Current Halo Infinite Season
 * `AUTOCODE_CURRENT_SEASON_VERSION` - Current Halo Infinite Season Version
 * `AUTOCODE_WARNING_MESSAGE` - If populated, puts site into warning mode.

#### XboxApi
Used for resolving XUIDs so renames are possible without data loss.

 * `XBOXAPI_DOMAIN` - The base domain for [Unofficial XboxAPI](https://xbl-api.prouser123.me/).
 * `XBOXAPI_ENABLED` - Boolean to enable the XUID resolving or not.

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

Nginx listens at your local IP address, port 8080, e.g. http://localhost:8080

### Dependencies

#### Node
1. Bulma - https://github.com/jgthms/bulma - MIT
2. FontAwesome - https://github.com/FortAwesome/Font-Awesome - Font Awesome Free License
3. Bulma-Dividers - https://github.com/CreativeBulma/bulma-divider - MIT
4. Bulma-Tooltip - https://github.com/CreativeBulma/bulma-tooltip - MIT
5. Bulma-Ribbon - https://github.com/Wikiki/bulma-ribbon - MIT

#### PHP
1. PHP CodeSniffer - https://github.com/squizlabs/PHP_CodeSniffer - BSD-3-Clause 
2. Larastan - https://github.com/nunomaduro/larastan - MIT
3. Paratest - https://github.com/paratestphp/paratest - MIT
4. Guzzle - https://github.com/guzzle/guzzle - MIT
5. Livewire - https://github.com/livewire/livewire - MIT
6. Enum - https://github.com/BenSampo/laravel-enum - MIT
7. DBAL - https://github.com/doctrine/dbal - MIT
8. SEOTools - https://github.com/artesaos/seotools - MIT
9. TheLeague/CSV - https://github.com/thephpleague/csv - MIT
10. Socialite - https://github.com/laravel/socialite - MIT
11. Sitemap - https://github.com/spatie/laravel-sitemap - MIT
12. Horizon - https://github.com/laravel/horizon - MIT
