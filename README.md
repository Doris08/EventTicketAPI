# Event Management and Ticketing API

## Prerequisites

Ensure that your machine meets the following requirements before you begin:
[Docker](https://www.docker.com/get-started) installed
[Docker Compose](https://docs.docker.com/compose/install/) installed

For more information about sail read this:
[Laravel Sail](https://laravel.com/docs/10.x/sail)

## Installation

1. Open a terminal and clone the repository from GitHub:
   https://github.com/Doris08/EventTicketAPI.git

    like this:
    `git clone https://github.com/Doris08/EventTicketAPI.git`

2. Navigate to the project directory:
   `cd EventTicketAPI`

3. Copy the `.env.example` file to configure your `.env` file:
   `cp .env.example .env`

4. Generate an application key for your project:
   `php artisan key:generate`

5. Open the `.env` file and configure the necessary environment variables, such as database settings.

6. Run the following command to start the development environment using Laravel Sail:
   `./vendor/bin/sail up -d`

7. Install PHP dependencies using Composer:
   `./vendor/bin/sail composer install`

8. Run database migrations and seed the database with initial data:
   `./vendor/bin/sail artisan migrate`

## Configuration

1.  If you want to use PhpMyAdmin add this to your `docker-compose.yml` file:

```
...
depends_on:
-mysql
-phpmyadmin
...
```

2. Go into your services, and add it as a service. We need to make sure to add sail’s network, or else we won’t be able to access our mysql.

```
phpmyadmin:
   image: 'phpmyadmin:latest'
   ports:
      8080:80
   networks:
      sail
   environment:
      PMA_ARBITRARY=1
```

3. Go to your `.env` file and add the parameters
   `STRIPE_KEY` and `STRIPE_SECRET`

    For more information about Stripe read this:
    [Stripe Api Documentation](https://stripe.com/docs/api)

## Usage

Yo can test the API using an API plattform as Postman or Insomnia, for a better understanding of the url requests and responses you can consult the API's documentation in the following link:

[API Documentation](https://api-event-tickets.stoplight.io/docs/apiticketevents/branches/main)

Now that your project is installed and running, you can run the tests with the following command:
`./vendor/bin/phpunit`
