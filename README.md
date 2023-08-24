# PETSHOP APP

## Setup Instructions

### With Docker (Recommended)

- Run `cp .env.example .env`. This should set up the environment variables.
- Run `docker compose up`. This should bring up the app and its other services (Redis and MySQL).
- Run ```docker exec -it petshop_app bash``` to enter into the docker container for the laravel app. And then run `composer install`.
- Run the command `alias petshop_php="docker exec petshop_app php"`. This should set an alias to make running php artisan commands more convenient. Alternatively, you can exec into the container first and then run the normal `php artisan` commands.
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
- Run `composer install`.
- Set up your database with the db credentials in the .env file.
- Run `php artisan migrate`. This should run the migration to setup the database. Notice that we are using the alias defined in the previous step.
- Run `php artisan key:generate`. To Setup application key in the environment variables.
- You should see the `JWT_SECRET` key in your `.env` file. You should manually add it if not found. Any randomly long string will do.
- Run `php artisan db:seed`. This will seed the required user data into your database.


### API Documentation
All API endpoints are documented, adn can be found in the `references` folder.


### Running Tests

A note on running the tests. The test files use a trait known as `RefreshDatabase`. This trait clears the database after each iteration of the test. Hence, if you do not want you database to be cleared, ensure you create a `.env.testing` file with another database that will be used for testing.

You can run `petshop_php artisan test`. To run all tests.


### Running Code Checker 

This project has PHP insights package installed. To run php insights
- Exec into the container `docker exec -it petshop_app bash`
- Run `./vendor/bin/phpinsights`

This project also has Larastan installed. You can run it using the command `./vendor/bin/phpstan analyse`


### Petshop Notifier Package

A petshop notifer package has been developed as part of this project. You can find access to it's full README documentation here => https://github.com/IDTitanium/petshopnotifier#readme. 

The documentation explains how to set it up for this project.
