## PETSHOP APP

### Setup Instructions

#### With Docker

- Run `cp .env.example .env`. This should set up the environment variables.
- Run `docker compose up`. This should bring up the app and its other services (Redis and MySQL).
- Run the command `alias petshop_php="docker exec petshop_app php"`. This should set an alias to make running php artisan commands more convenient.
- Run `petshop_php artisan migrate`. This should run the migration to setup the database. Notice that we are using the alias defined in the previous step.
- Run `petshop_php artisan key:generate`. To Setup application key in the environment variables.
- Run `petshop_php artisan jwt:secret`. This is to generate a jwt secret. You should see the `JWT_SECRET` key in your `.env` file. You can manually add it if not found.
- Run `petshop_php artisan db:seed`. This will seed the required user data into your database.

### Running Tests

A note on running the tests. The test files use a trait known as `RefreshDatabase`. This trait clears the database after each iteration of the test. Hence, if you do not want you database to be cleared, ensure you create a `.env.testing` file with another database that will be used for testing.