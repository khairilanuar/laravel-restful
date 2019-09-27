# Laravel-RESTful
by Infinitum

## About
a RESTful API server boilerplate to quickstart your Laravel API server project.

We found that Lumen is too light and we simply love Laravel more. However some serious modification has been made to 
Laravel in order to make it faster and lighter, such as disabling Session and Cookies support.  

This project is pre-installed (and pre-configured) with some useful (and famous!) composer packages such as:

- Official OAuth2 by Laravel Passport
- User management by [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
- Repository pattern by [Prettus](https://github.com/andersao/l5-repository)
- Request encryption by [Crypton](https://github.com/tzsk/crypton) see [laravel-crypton](https://github.com/tzsk/laravel-crypton) for Vue / JS adapter
- CSP and Secure headers by [bepsvpt/secure-headers](https://github.com/BePsvPT/secure-headers)
- CORS management by [spatie/laravel-cors](https://github.com/spatie/laravel-cors)
- Audit trail management by [owen-it/laravel-auditing](https://github.com/owen-it/laravel-auditing)
- Semantic versioning by [Pragmarx](https://github.com/antonioribeiro/version)
- Laravel Excel by [Maatwebsite](https://github.com/maatwebsite/Laravel-Excel)

Development Packages:

- Parallel PHP unit testing by [paratest](https://github.com/paratestphp/paratest)
- PHP coding style by [friendsofphp/php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer)
- PHP coding style by [squizlabs/PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- Laravel IDE Helper by [Barryvdh](https://github.com/barryvdh/laravel-ide-helper)
- API documentation generator by [mpociot/laravel-apidoc-generator](https://github.com/mpociot/laravel-apidoc-generator)
- **TODO:** CI/CD Pipeline


For full listing of 3rd party packages used in this project please have a look in `composer.json`


## Getting Started `(WIP)`

#### Pre-requisites `(WIP)`:
- libXrender wkhtmltopdf

1. clone this repository
1. perform `composer install`
1. `cp .env.example .env` and adjust to your need
1. `php artisan key:generate`
1. (optional) customize configuration (in `config/` directory)
1. set your database connection parameter in `.env`
1. `php artisan migrate`
1. `php artisan passport:install`
1. `php artisan db:seed`

## Contributing
Please follow standard Laravel coding style, check your code with pre-defined composer scripts:

- To check PHP code styling: `composer cs:check`
- To attempt fix PHP code styling: `composer cs:fix`
- Make sure your code is covered in test cases: `composer test:coverage`
- Run unit test: `composer test:unit`

If everything is good you can continue to submit your pull request to 'development' branch.


## License
This code is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
