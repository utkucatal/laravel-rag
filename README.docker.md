# Laravel Docker Dev Environment

PHP 8.4 + Apache + PostgreSQL 16 + MySQL 8.4 + MariaDB 11.4. Tuned for Laravel,
Livewire, Tailwind/Vite, and Xdebug. Built/verified on Apple Silicon (M2, arm64).

## Services & ports (host)

| Service    | URL / Port           | Notes                                  |
|------------|----------------------|----------------------------------------|
| webserver  | http://localhost:8080| Apache -> php-fpm, serves `src/public/`|
| app        | (php-fpm 9000 internal) | PHP 8.4, composer, node 22, xdebug  |
| vite       | localhost:5173       | Tailwind/Vite HMR (run inside `app`)   |
| postgres   | localhost:5432       | **default** DB connection              |
| mysql      | localhost:3307       | host 3307 -> container 3306            |
| mariadb    | localhost:3308       | host 3308 -> container 3306            |

Xdebug -> IDE on port **9003** (`host.docker.internal`), `idekey=PHPSTORM`.

Change any port in `.env`. No two DBs share a host port.

## Data persistence (important)

DB data is **bind-mounted** to the host, so it survives container teardown:

```
docker/data/postgres   docker/data/mysql   docker/data/mariadb
```

`docker compose down` removes containers but NOT this data. Next `up` reuses it —
no reinstall. (Only deleting these folders, or `down -v`, wipes data.)

> macOS: Docker Desktop must share the project path (Settings → Resources →
> File Sharing). Paths under your home dir are shared by default.

## Usage

```bash
docker compose build
docker compose up -d
```

## Install Laravel

The app lives in `src/` (Apache DocumentRoot = `src/public`, php-fpm `working_dir = /var/www/html/src`).
`src/` is a clean, separate folder — no merge tricks needed. Install straight into it:

```bash
docker exec -it laravel_app bash
composer create-project laravel/laravel .
```
Don't forget the change .env db details. Switch DB:
```bash
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```
save
```bash
php artisan config:clear 
php artisan migrate
```

## Livewire + Tailwind

```bash
composer require livewire/livewire
npm install -D tailwindcss @tailwindcss/vite
```

For Vite HMR to work from the container, set `src/vite.config.js` ONCE (this is
permanent — after this just run `npm run dev`, no CLI flags to remember):

```js
  server: {
      host: '0.0.0.0',                 // bind all interfaces (host can reach)
      port: 5173,
      strictPort: true,
      cors: true,                      // allow :8080 page to load :5173 assets
      origin: 'http://localhost:5173', // advertise localhost, not [::] -> fixes CORS/null errors
      hmr: { host: 'localhost' },      // HMR websocket connects back to localhost
  },
```

Then start the dev server (config handles the host — no `--host` flag needed):

```bash
npm run dev
```

> Without `server.origin`, Vite advertises assets as `http://[::]:5173` (IPv6
> wildcard, unreachable) and the browser console fills with "CORS request failed /
> Status (null)" errors. `origin` + `cors` fix it.

No HMR needed? Skip the dev server entirely: `npm run build` compiles assets into
`public/build/`, served same-origin by Apache (`:8080`) — zero CORS. Re-run on change.


## Quick start (recap)

```bash
docker compose up -d --build

docker exec -it laravel_app bash
composer create-project laravel/laravel .

# edit src/.env DB block by hand
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

php artisan config:clear
php artisan migrate
npm run dev
```

Browser: http://laravel.loc:8080 (add `127.0.0.1 laravel.loc` to hosts) or http://localhost:8080.
Port **8080** is required — `hosts` maps name->IP only, not the port.
