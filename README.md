# Simple RSS aggregator

A very simple project for testing Laravel 5.8 with Docker installation process.

## Before you start

If you are planning to run the project on Windows please use attached `env-example-win`

## How to setup

To run the project locally you have to use the following commands:

```
cp env-example .env

# Modify environment file to suit your needs

docker-compose up -d
docker-compose exec php-fpm php composer.phar install

# Please allow up to 10 minutes to docker to install and configure time zone data for MariaDB.

docker-compose exec mariadb mysql -Bsse "CREATE DATABASE IF NOT EXISTS `default`"
docker-compose exec mariadb mysql -Bsse "GRANT ALL ON `default`.* TO 'default'@'%' IDENTIFIED BY 'secret'"
docker-compose exec mariadb mysql -Bsse "FLUSH PRIVILEGES"

docker-compose exec php-fpm php artisan migrate
```

## Open your browser and visit localhost: http://localhost.