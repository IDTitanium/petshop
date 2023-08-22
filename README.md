# PETSHOP APP

## Setup Instructions

### With Docker (Recommended)

- Run `cp .env.example .env`. This should set up the environment variables.
- Run `docker compose up`. This should bring up the app and its other services (Redis and MySQL).
- Run the command `alias petshop_php="docker exec petshop_app php"`. This should set an alias to make running php artisan commands more convenient.
- Run `petshop_php artisan migrate`. This should run the migration to setup the database. Notice that we are using the alias defined in the previous step.
- Run `petshop_php artisan key:generate`. To Setup application key in the environment variables.
- You should see the `JWT_SECRET` key in your `.env` file. You should manually add it if not found. Any randomly long string will do.
- Run `petshop_php artisan db:seed`. This will seed the required user data into your database.

### Without docker
- To run this project without docker, you need to ensure you meet the following requirements.
    - PHP 8.2 installed
    - MySQL ^8.0 database
    - Composer installed
    - Redis installed (optional)

Then follow the instructions

- Run `cp .env.example .env`. This should set up the environment variables.
- Run `php artisan migrate`. This should run the migration to setup the database. Notice that we are using the alias defined in the previous step.
- Run `php artisan key:generate`. To Setup application key in the environment variables.
- You should see the `JWT_SECRET` key in your `.env` file. You should manually add it if not found. Any randomly long string will do.
- Run `php artisan db:seed`. This will seed the required user data into your database.
- Run `php artisan db:seed`. This will seed the required user data into your database.


### Running Tests

A note on running the tests. The test files use a trait known as `RefreshDatabase`. This trait clears the database after each iteration of the test. Hence, if you do not want you database to be cleared, ensure you create a `.env.testing` file with another database that will be used for testing.

You can run `petshop_php artisan test`. To run all tests.


### Running Code Checker 

This project has PHP insights package installed. To run php insights
- Exec into the container `docker exec petshop_app`
- Run `./vendor/bin/phpinsight`

This project also has Larastan installed. You can run it using the command `./vendor/bin/larastan`

