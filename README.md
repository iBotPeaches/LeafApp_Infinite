# Leafapp (Infinite)
_Another hobby stat site for another Halo game_

### Branches
 * `master` - Deploys to `leafapp.co`

### Setup
1. PHP7.4 or PHP8
2. Node + Yarn installed
3. [Composer](https://getcomposer.org/) installed.
4. `cp .env.example .env`
5. `composer install`
6. `yarn install`
7. `php artisan key:generate`
8. `php artisan migrate`
9. `yarn run dev`
10. `php artisan serve`

### Configuration

#### HaloDotApi
Used for the API for Infinite information, an amazing service that proxies some internal HaloWaypoint/Live calls.

 * `HALODOTAPI_KEY` - The API key given after the OAuth Live dance


### Dependencies

#### Node
1. Tailwind - https://github.com/tailwindlabs/tailwindcss - MIT

#### PHP
1. PHP CodeSniffer - https://github.com/squizlabs/PHP_CodeSniffer - BSD-3-Clause 
2. Larastan - https://github.com/nunomaduro/larastan - MIT
3. Paratest - https://github.com/paratestphp/paratest - MIT
