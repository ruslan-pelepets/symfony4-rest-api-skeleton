# Symfony4ApiRest

Simple example of an API REST with http basic authorization with Symfony 4

## Install with Composer

```
    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install or composer install
```

## Setting Environment

```
    $ cp .env.dist .env
```

## User Authentication with Curl 

```
    $ curl -H 'content-type: application/json' -v -X GET http://127.0.0.1:8000/api/address/items  -H 'Authorization:Basic username:password or email:password' 
```

## Getting with Phpunit

```
    $ phpunit or ./bin/phpunit
```
