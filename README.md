## About
Web application for display user lessons, developed by VueJS + Laravel

### Installation
The example demonstrates launching an application using Docker, [see other ways to launch Laravel app](https://laravel.com/docs/8.x)

Clone the repository

`git clone git@github.com:ararati/list-user-courses.git`

Switch to the repo folder

`cd list-user-courses`

Install all the dependencies

`composer install`

`npm install`

Copy the example env file and set the required configuration changes

`cp .env.example .env`

Run docker containers

`./vendor/bin/sail up`


Run the database migrations

`./vendor/bin/sail artisan migrate`

You can now access the server at http://127.0.0.1:80

***Optional:*** Launching Vue application with hot reload:

`npm run hot`

### Database seed

Seed database

`./vendor/bin/sail artisan db:seed`

Clean database

`./vendor/bin/sail artisan migrate:refresh`

### Test
`./vendor/bin/sail artisan test`
