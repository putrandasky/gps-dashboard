

## About Application

The application is a dashboard from Amtiss.com with GPSWOX API

### Prequisities

1. Composer installed in server
2. NVM  installed in server

## How to Install

1. rename ***.env.example*** to ***.env***
2. generate application key with
```
php artisan key:generate
```
3. install depedency for PHP
```
composer install
```
4. install depedency for javascript production
```
npm install --only=prod
```
5. make sure domain/subdomain root folder to public folder
```
.../gps_dashboard_folder/public
```
