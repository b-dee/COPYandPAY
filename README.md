# COPYandPAY
## A small app to test the COPYandPAY API.

Apologies for the rush job last time! I've been the only develeoper implementing a new feature (in-browser preview of securely uploaded files of various types) for the last 2 weeks. I've never had a test app come back before, but I wasn't too surprised if I'm honest! Thanks for letting me fix it.

All core requirements are met. I've left the additional for now.

Since I'm not sure exactly how or where you plan to run this app I've had to make some assumptions. I went for the most self-contained option when bootstrapping it, using Sail rather than the Laravel Installer. One command should bring up all required services in Docker containers, leaving your existing development environment (if any) alone. The trade-off is that you will need Docker installed. Apologies if you don't already have this!

## To run the app:

Only a (PHP enabled) web server, the MySQL database and a Redis instance are required. The PhpRedis PHP extension will need to be installed via PECL if NOT using the docker containers, since the PHP container already has it.

Port setting are the defaults for HTTP, MySQL and Redis, but can probably be changed via .env.

You'll need to set the following variables in .env. I didn't want to chance yours being sensitive (though I assume they aren't).

COPY_AND_PAY_ENTITY_ID=... (from your test spec)

COPY_AND_PAY_ACCESS_TOKEN=... (from your test spec)

SESSION_DRIVER=database (or delete this variable so that it picks up the default)

If you already have a development environment (apache, nginx etc.) you may be able to use that. You might even get away with running `php artisan serve` depending on your setup. 

If not, spin up the containers:

1. Install Docker (Docker Desktop on Mac/Win) from [here](https://docs.docker.com/get-docker/) if you don't already have it. If you're on Linux, you'll also need to install Docker Compose separately from [here](https://docs.docker.com/compose/install/).  
2. Clone this repository and change to its root directory.
3. Install the composer dependencies by running

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```
4. (Optional) Add vendor binaries to PATH for this shell session for convenience: `export PATH=$PATH:./vendor/bin`.
5. Run `sail up -d` to create and start containers for the laravel app, MySQL and Redis. 
6. Run the database migrations to set up the database tables: `sail php artisan migrate`.

## To stop the app:

1. From the repository's root directory run `sail down` to stop and clean up the containers.

## To run the unit tests

Run `sail php artisan test` to kick off PHPUnit.

Note: I usually aim for as close to 100% code coverage as possible for unit tests, so that I can tell immediately if regressions creep in even before pushing to the remote. Here I've just written one test for demonstration purposes, rather than fully covering the entire test app. I hope that's ok!

## Changes since last time...

- Validate the checkout ID is recognised when displaying the transaction result.
- Verify transaction status response fields (those recommended in the docs).
- Move to database backed sessions. Nothing is persisted to the session currently, but future-proofing won't hurt.
- Store payment brand and payment ID (reference) for future back office operations (refunds etc.).
- Paginate the results of the transaction history query.
- Cache the results of the transaction history query on a per page basis.
- Add error checking on all responses and around the payment flow. 
- Display HTTP Exceptions with (minimalist) custom views.
- Refactor the PaymentController, create some helpers and generally clean up the code. Looks much better now!
- Use result code regular expressions to evaluate the whole range of success responses instead of using a single code.
- Put unique constraint on our merchant transaction ID.
- Beef up validation a bit.
- Minor UI tweaks here and there.
- Add a unit test.

Any problems, get in touch and I'll be more than happy to help.

## Possible future improvements

- Move to named routes to tidy up routing a bit.
- Store the payment model against the checkout ID in the session (or a separate cookie, 30 min TTL) here rather than requesting a new ID each time the form is submitted. It's valid for 30 mins according to the docs, so we could cache it to save a HTTP request. We'd need to provide the user a button to "forget" the transaction in progress and request a new ID, and do this automatically if used afterwards.
