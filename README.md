# Leaf (Infinite)
[![Laravel](https://github.com/iBotPeaches/LeafApp_Infinite/actions/workflows/laravel.yml/badge.svg)](https://github.com/iBotPeaches/LeafApp_Infinite/actions/workflows/laravel.yml)

_Another hobby stat site for another Halo game_

### Branches
 * `master` - Branch off it. Must be stable.

### Setup
1. PHP8.1
2. Node + Yarn installed
3. [Composer](https://getcomposer.org/) installed.
4. `cp .env.example .env`
5. `composer install`
6. `yarn install`
7. `php artisan key:generate`
8. `php artisan migrate`
9. `yarn run dev`
10. `php artisan serve`

### Contributions
 * Code must pass phpcs. (`./vendor/bin/phpcs`)
 * Code must pass phpstan. (`./vendor/bin/phpstan analyse`)
 * Code must have 100% test coverage. (`composer coverage`)

### Configuration

#### HaloDotApi (Autocode)
Used for the API for Infinite information, an amazing service that proxies some internal HaloWaypoint/Live calls.

 * `AUTOCODE_KEY` - The API key given [Autocode](https://autocode.com/lib/halo/)
 * `AUTOCODE_DOMAIN` - The base domain for Autocode
 * `AUTOCODE_VERSION` - The version of the Autocode to use
 * `AUTOCODE_COOLDOWN` - The amount of time to wait between refreshing profiles automatically.

#### XboxApi
Used for resolving XUIDs so renames are possible without data loss.

 * `XBOXAPI_DOMAIN` - The base domain for [Unofficial XboxAPI](https://xbl-api.prouser123.me/).

### Dependencies

#### Node
1. Bulma - https://github.com/jgthms/bulma - MIT
2. FontAwesome - https://github.com/FortAwesome/Font-Awesome - Font Awesome Free License
3. Bulma-Dividers - https://github.com/CreativeBulma/bulma-divider - MIT
4. Bulma-Tooltip - https://github.com/CreativeBulma/bulma-tooltip - MIT

#### PHP
1. PHP CodeSniffer - https://github.com/squizlabs/PHP_CodeSniffer - BSD-3-Clause 
2. Larastan - https://github.com/nunomaduro/larastan - MIT
3. Paratest - https://github.com/paratestphp/paratest - MIT
4. Guzzle - https://github.com/guzzle/guzzle - MIT
5. Livewire - https://github.com/livewire/livewire - MIT
6. Enum - https://github.com/BenSampo/laravel-enum - MIT
7. DBAL - https://github.com/doctrine/dbal - MIT
