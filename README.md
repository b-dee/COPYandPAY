# COPYandPAY
### A small app to test the COPYandPAY API.

Admittedly this is a little bit of a rush job. Busy week leading the development of a new feature. All core requirements are met. I didn't get to the additional.

Since I'm not sure exactly how or where you plan to run this app I've had to make some assumptions. I went for the most self-contained option when bootstrapping it, using Sail rather than the Laravel Installer. One command should bring up all required services in Docker containers, leaving your existing development environment (if any) alone. The trade-off is that you will need Docker installed. Apologies if you don't already have this!

## To run the app:

Only a (PHP enabled) webserver and the MySQL database are required.

Port setting are the defaults for HTTP and MySQL, but can probably be changed via .env.

You'll need to set the following two variables in .env to the ones in the test spec. I didn't want to chance them being sensitive (though I assume they aren't).

COPY_AND_PAY_ENTITY_ID=...
COPY_AND_PAY_ACCESS_TOKEN=...

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

4. Run `./vendor/bin/sail up -d` to create and start containers for the laravel app and mysql. 
5. Run the database migrations to set up the database tables: `./vendor/bin/sail php artisan migrate`.

## To stop the app:

1. From the repository's root directory run `./vendor/bin/sail down` to stop and clean up the containers.

Any problems, get in touch and I'll be more than happy to help.

## If I had more time I'd...

- Refactor the PaymentController. It grew in-place, and I usually code much cleaner than this. 
- Put in more error checking. You'll see a shortage of that.
- Display those errors properly. Right now it just outputs unhelpful strings in lieu of proper error views.
