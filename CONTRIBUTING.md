# Contributing

* Report bugs and request features on the [issue tracker](https://github.com/zeropingheroes/lanager/issues).
* Check the [issue tracker](https://github.com/zeropingheroes/lanager/issues) for features and bug fixes to work on.
* [Fork the project](https://github.com/zeropingheroes/lanager/fork) and add the features you want to see.
* Read the [pull request checklist](#pull-request-checklist) to make sure your code is ready to be merged.
* Send a [pull request](https://github.com/zeropingheroes/lanager/pulls) with your changes.

## Contents

* [Copyright ownership](#copyright-ownership)
* [Architecture](#architecture)
* [Development environment setup](#development-environment-setup)
* [Pull request checklist](#pull-request-checklist)

## Copyright ownership

By contributing code to this project, you hereby assign copyright in this code to the project, to be licensed under the
same terms as the rest of the code.

This is to prevent the logistical and legal nightmare if contributors retained copyright to code contributed to this
open source project. For an in-depth explanation, see [Who owns the copyright for an open source project](http://haacked.com/archive/2006/01/26/WhoOwnstheCopyrightforAnOpenSourceProject.aspx).

### AUTHORS.txt

If your contribution is accepted into the project, you may wish for your name and e-mail address to be added to
AUTHORS.txt. If this is the case, include a modification with your pull request.

## Architecture

### Branches

* `stable` - Current release.

* `develop` - Unstable development for the next release. Always submit pull requests against this branch.

### Tech stack

* [Laravel 12](https://laravel.com/docs/12.x)
* [PHP 8.4](https://www.php.net/releases/8.4/en.php)
* [FrankenPHP](https://frankenphp.dev/docs/)
* [Laravel Octane](https://laravel.com/docs/12.x/octane)

### Namespace & autoloading

* All application code lives under `Zeropingheroes\Lanager\` mapped to `app/`.
* There is no `App\` namespace.

### Authentication & authorisation

* Users authenticate exclusively via Steam OAuth (Laravel Socialite + `socialiteproviders/steam`).
* The first user to log in is automatically assigned the `super-admin` role.
* Authorisation uses Laravel Policies
* `BasePolicy::before()` grants super-admins all permissions unconditionally
* All policies extend `BasePolicy`.
* Roles are stored in the `roles` table, assigned via `role_assignments` (pivot uses the `RoleAssignment` model).

### Current LAN concept

* `CurrentLanController` redirects users to pages associated with the LAN that is currently happening
  (`Lan::happeningNow()`), or falls back to the most recent past LAN.

### Steam integration

* Scheduled `artisan` commands (`app/Console/Commands/`) poll the Steam Web API via 
  the [zeropingheroes/steam-apis](https://github.com/zeropingheroes/steam-apis) package.
* `UpdateSteamUsers` updates user metadata and records `SteamUserAppSession` records (gameplay sessions) for
  current LAN attendees.
* `UpdateSteamUserApps` / `UpdateSteamApps` sync Steam library and app catalogue data.
* The `SteamUserAppSession` model tracks per-user gameplay sessions with `start`/`end` timestamps
* Stale sessions are auto-ended.

### Frontend

* [Blade templates](https://laravel.com/docs/12.x/blade) for server-rendered pages (`resources/views/`).
* [Laravel Breadcrumbs](https://github.com/diglactic/laravel-breadcrumbs) for breadcrumb navigation.
* [Laravel Markdown](https://github.com/GrahamCampbell/Laravel-Markdown) for server-side Markdown rendering.
* [Vue 3](https://vuejs.org/guide/introduction.html) for interactive components (`resources/js/components/`, `resources/js/pages/`), with per-page JS entry
  points registered in `vite.config.js`.
* [Bootstrap 5](https://getbootstrap.com/docs/5.3/getting-started/introduction/) for styling, with an SCSS entry 
  point at `resources/css/app.scss`.
* [FullCalendar](https://fullcalendar.io/docs) for the event schedule view.
* [Font Awesome](https://docs.fontawesome.com/) for icons.
* [Tempus Dominus](https://getdatepicker.com/6/) for datetime pickers.
* [Vite](https://vitejs.dev/guide/) for asset bundling.
* Navigation links are cached forever (`Cache::rememberForever`)

### JSON API

* The JSON API under `/api/` (routes in `routes/api.php`, controllers in `app/Http/Controllers/Api/`) is mostly
  read-only and public, used by the Vue slides player and the active-games display.
* All routes share a single `api` middleware group (`statefulApi()` enabled). The state-changing actions additionally 
  carry `auth:sanctum`, which accepts either the browser's own session (first-party requests from the app's own origin)
  or a Laravel Sanctum personal access token (external clients), authorized against the same Policies as the rest of the
  app.
* A logged-in user can issue and revoke their own personal access tokens from the "API Tokens" page, linked from
  the account menu (`app/Http/Controllers/ApiTokenController.php`).

### Slides

* The slides feature (`lans/{lan}/slides/play`) is a Vue-driven fullscreen slideshow consuming the API to display
  live data (current games, upcoming events) for venue displays.

## Development environment setup

1. Follow the steps from the *Setup* section in [README.md](README.md).

2. Stop the running containers:

    ```bash
    docker compose down
    ```

3. Check out the development branch of `lanager-docker-compose`:

    ```bash
    cd lanager-docker-compose
    git checkout develop
    ```

4. Edit `lanager-docker-compose/.env` and add the following lines:

    ```bash
    APP_ENV=local
    APP_DEBUG=true
    ```

5. In a directory outside of `lanager-docker-compose`, clone the `lanager` repository:

    ```bash
    git clone --branch develop https://github.com/zeropingheroes/lanager
    ```

6. Set an environment variable with the path to where you cloned the `lanager` repository (without a trailing slash):

    ```bash
    export PATH_TO_LANAGER=/path/to/lanager
    ```

7. From the `lanager-docker-compose` directory, run `envsubst` to substitute in the path to lanager into the override
   compose file:

    ```bash
    envsubst < compose.override.yaml.example > compose.override.yaml
    ```

8. Install composer dependencies using a temporary container:

    ```bash
    docker run --rm --name composer-install -v "$PATH_TO_LANAGER":/lanager -w /lanager composer:2 install --no-scripts
    ```

9. Set the correct permissions for the `storage` and `bootstrap/cache` directories:

    ```bash
    chmod -R 777 "$PATH_TO_LANAGER/storage" "$PATH_TO_LANAGER/bootstrap/cache"
    ```

10. Create a symbolic link from the app storage directory into the public directory:

    ```bash
    ln -s "$PATH_TO_LANAGER/storage/app/public" "$PATH_TO_LANAGER/public/storage"
    ```

11. Build frontend assets:

    ```bash
    docker run --rm --name npm-install -v "$PATH_TO_LANAGER":/lanager -w /lanager node:22 npm clean-install
    docker run --rm --name npm-build -v "$PATH_TO_LANAGER":/lanager -w /lanager node:22 npm run build
    ```

12. Start the containers:

    ```bash
    docker compose up --detach
    ```

13. After a minute or so, visit [http://localhost:8080](http://localhost:8080).

The container will run the code from your host computer, rather than the static copy of the code in the container's
image. Any changes you make to the files in the project directory (except for the `storage/` directory)
will be seen by the running containers.

### Start and stop the development environment

```bash
docker compose start
docker compose stop
```

### Destroy the development environment

To destroy the development environment and all volumes that store lanager data, run:

```bash
docker compose down --volumes
```

## Pull request checklist

Before submitting a pull request:
* Only include one feature or bugfix in your pull request.
* Add tests for your feature or bugfix.
* Update `README.md` with any relevant changes.
* Add yourself to `AUTHORS.txt`.
* Run the below commands to check your code's quality.

### Run Pest unit and feature-test suite

```bash
docker exec -it lanager ./vendor/bin/pest
```

### Run Laravel Dusk browser test suite

```bash
docker exec --env="APP_URL=http://lanager:8000" --env="APP_ENV=testing" --env="APP_DEBUG=false" -it lanager php artisan dusk
```

Dusk tests use a separate config (`phpunit.dusk.xml`) from Pest's Unit/Feature tests (`phpunit.xml`).

### Run PHPStan static analysis

```bash
docker exec -it lanager ./vendor/bin/phpstan analyse
```

### Check Rector refactoring suggestions

```bash
docker exec -it lanager ./vendor/bin/rector --dry-run
docker exec -it lanager ./vendor/bin/rector # optional - applies the suggestions
```

### Run Laravel Pint code style fixer

```bash
docker exec -it lanager ./vendor/bin/pint
```
