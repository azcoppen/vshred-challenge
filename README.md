# VShred Challenge

## Overview


* Create a new Laravel 6 project.
* Ensure all code is formatted according to the PSR-2 PHP coding standard.

* Install the following composer packages:
** Laravel Passport (https://laravel.com/docs/6.x/passport)
** Spatie Query Builder (https://github.com/spatie/laravel-query-builder)
** Spatie Laravel Permissions (https://github.com/spatie/laravel-permission)

* Add an images model that has a many-to-one relationship with the built-in Laravel users model

* Users should be soft deleted

* Login resource should return a Passport auth token after a successful login

* Using the Laravel Permissions package, create a single role called "admin"

* Controllers should be RESTful and have route model binding.

* Seed standard test users with no role and test users with the "admin" role
** Seeded regular users should get one or more example images

* Using the Query Builder package, build a RESTful "users" resource using a Respository-Resource-Controller pattern

* The resource should allow the following RESTful actions:
** index, show, store, update, delete
** Store and update endpoints should have appropriate validation rules in the request
** Store endpoint should fire a welcome email event after user creation
** Images should only append on the user resource when requested in the API call as an include
** Index API call should be able to filter by user's email

* Limit the action of the user resource such that:
** An admin can take all actions and see all users in the index
** A regular user can only view and update themselves

* Project should be usable via a standard "php artisan serve" command and routes should be testable over Postman

* Provide email and password for at least one regular user and one admin user for evaluation

## Setup

```
git clone https://github.com/azcoppen/vshred-challenge
cd vshred-challenge
cp ,env.example .env
touch storage/app.sqlite
composer -v install
npm install && npm run dev
php artisan app:setup
php artisan serve
```

## Testing

Insomnia YAML is is `docs/insomnia` folder. Not a lot of time to write tests.

Get all users:

```
curl --request GET --url 'http://127.0.0.1:8000/api/users?include=images' --header 'accept: application/json' --header 'authorization: Bearer <your-token-here>'
```

Store a user:

```
curl --request POST \
  --url http://127.0.0.1:8000/api/users \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <your-token-here>' \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data 'name=New User' \
  --data email=someone@example.com \
  --data password=12345
```

View a user:

```
curl --request GET \
  --url http://127.0.0.1:8000/api/users/2 \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <your-token-here>'
```

Update a user:

```
curl --request PATCH \
  --url http://127.0.0.1:8000/api/users/10 \
  --header 'authorization: Bearer <your-token-here>' \
  --header 'accept: application/json' \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data 'name=Updated Name'
```

Destroy a user:

```
curl --request DELETE \
  --url http://127.0.0.1:8000/api/users/7 \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <your-token-here>'
```

### Notes

 - This is Laravel 8, NOT 6
 - PSR-2 sucks
 - Spatie's Query Builder isn't a repository pattern, and it's a horribly-written package which needs to be burnt with fire. This is: https://github.com/andersao/l5-repository
 - Not enough time to do logins etc, but you'll get them on the command line with setup. The new Jetstream package is a nightmare to work with (overwrites your User model, for a start)
 - Permissions are better than roles: https://spatie.be/docs/laravel-permission/v3/best-practices/roles-vs-permissions
 - Laravel Resources are god-awful. Fractal is far more useful: https://github.com/spatie/laravel-fractal
 - Postman is a resource hog. Insomnia is nicer.
 - Users 1-3 are admins, the rest are guests. All passwords are 12345.
