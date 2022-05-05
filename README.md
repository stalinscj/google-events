# GoogleEvents


## _The Google Calendar Events Wrapper_

GoogleEvents is a restful API to manage events and sync with your Google calendar.


## Features

- Test Driven Development (TDD).
- OAuth system.
- Events CRUD.


## Technologies

- [Laravel 9] - Laravel is a web application framework with expressive, elegant syntax.
- [MySQL] - MySQL is the world's most popular open source database.
- [PostgreSQL] - The World's Most Advanced Open Source Relational Database.
- [PHP] - PHP is a popular general-purpose scripting language that is especially suited to web development.
- [PHPUnit] - PHPUnit is a programmer-oriented testing framework for PHP.


### Requirements

- Composer >= 2.1.12
- Git >= 2.11
- MySQL >= 8.0 o PostgreSQL >= 12.8
- SQLite >= 3.31.1
- PHP >= 8.0
- BCMath PHP Extension
- Ctype PHP Extension
- Curl PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- SQLite PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension


## Installation

```sh
git clone https://github.com/stalinscj/google-events.git
```

```sh
cd google-events
```

```sh
composer install
```

(If want use Docker instead):
```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```

(If .env file was not copied automatically after installation):

```sh
cp .env.example .env
```

(If APP_KEY was not generated automatically after installation):

```sh
php artisan key:generate
```

From MySQL or PostgreSQL CLI (ignore if are using Docker):

```sh
CREATE DATABASE database_name;
```

In .env file set the following variables:

```sh
APP_NAME=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URIS=
```

To get google credentials visit: [Create Client Credentials]

(If are using Docker):

```sh
sail up -d
```

```sh
php artisan migrate
```

(If are using Docker instead):

```sh
sail artisan migrate
```


## Running tests

```sh
php artisan test
```

(If are using Docker instead):

```sh
sail test
```

## Running project

(If are not using Docker ):

```sh
php artisan serve
```


From a browser go to http://127.0.0.1:8000 or http://127.0.0.1 if are using Docker.


## Docs

- API doc: https://documenter.getpostman.com/view/755693/UyxbqpKA


[//]: # (Links)

[Laravel 9]: <https://laravel.com>
[MySQL]: <https://www.mysql.com>
[PostgreSQL]: <https://www.postgresql.org>
[PHP]: <https://www.php.net>
[PHPUnit]: <https://phpunit.de>
[Create Client Credentials]: <https://cloud.google.com/docs/authentication/end-user?hl=es-419#creating_your_client_credentials>
