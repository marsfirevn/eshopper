# E-shopper
E-shopper: This is basic web shop online. Using [Laravel 5.3.x](https://laravel.com/docs/5.3)

## System requirements
- PHP version >= 5.6.4
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Setup
- Copy file `.env.example` to `.env`, `docker-compose.yml.example` to `docker-compose.yml`
- Modify `.env` config file (optional). If you modify the `mysql`, `mongo`, `redis` configurations in `.env` file, 
remember to modify the configurations in `docker-compose.yml` file too.
- Install or run Docker
```
docker-compose up -d

// Stop
docker-compose stop
```

- `chmod` cache folders
```
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

- Deploy
```
sh deploy.sh
```

## Troubleshoot
- If I want to run migration, what should I do?
```
// Check Docker Container list, copy the `workspace` container name
docker ps

// Go into the `workspace` container
docker exec -it teacher_workspace /bin/bash

// Run migration
php artisan migrate

// Or running outside the docker container
docker exec -it teacher_workspace php artisan migrate
```

## Coding Convention
### PHP
- https://github.com/wataridori/framgia-php-codesniffer
- Check style before creating a pull request
```
phpcs --standard=Framgia --encoding=utf-8 app/
```

- Auto fix style
```
phpcs --standard=Framgia app/
```

- Install [PHP Mess Detector](https://github.com/phpmd/phpmd)
```
composer global require "phpmd/phpmd"
```

- Use **PHP Mess Detector** to check potential problems
```
phpmd app text phpmd.xml
```

### Javascript
- Install `ESLint` and required plugins
```
sudo npm install -g eslint babel-eslint eslint-plugin-react
```

- Check Javascript style
```
npm test
```

- Check only files inside a specified folder
```
eslint resources/assets/js/
```

## Running Test
- Create database for **testing** purpose
- Update your `.env` file with the following information
```
DB_HOST_TESTING=localhost
DB_DATABASE_TESTING=awesome_db_testing
DB_USERNAME_TESTING=awesome
DB_PASSWORD_TESTING=awesome

```
- Running **migrations** before testing (if needed)
```
php artisan migrate --database=mysql_testing
```

- Running PHPUnit with Code Coverage
```
phpunit --coverage-html coverages
```
