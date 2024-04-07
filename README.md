Installation after cloning

## setup your .env
copy the contents of .env.example to your .env and set this keys below
- DB_CONNECTION=mysql
- DB_HOST=database
- DB_PORT=3306
- DB_DATABASE={databasename}
- DB_USERNAME={username}
- DB_PASSWORD={password}


## run docker commands below
- docker-compose build
- docker-compose up

## you can access your laravel app through this url
http://localhost:8000/

## to run Test file
- docker-compose ps
check the ## Service  (php) and get the name of it
- docker exec todo-list-laravel-graphql-backend-php-1 php artisan test
- test file tests\Feature\TodolistTest.php
