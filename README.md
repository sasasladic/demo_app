## About Application

Demo application - Structured to handle more games, but only Word Game is configured.

## Requirements
php: 8.0+

## Follow next steps to start application
1. clone it
2. copy .env.example into .env (in root of project)
3. Make database (example: demo_app)
4. edit DB_* connection variables in .env
5. In project root (terminal) run next commands: 
   1. composer install
   2. php artisan migrate --seed
   3. php artisan passport:install
   4. php artisan key:generate
   5. php artisan optimize:clear
   6. php artisan serve (alternative: point virtualHost to public/index.php)
7. optional: if you're using virtualHost change APP_URL in .env

## Test Login Credentials
email: test@test.com
pass: test123

## Feature/Unit Tests
Run: php artisan test

## Console application

Run: php artisan run:demo_app.<br>
PS. On login/registration password is hidden, so (probably) you will not see any characters

