# Simple laravel package to work with aws-php-sdk and amazon cognito

[![Latest Version on Packagist](https://img.shields.io/packagist/v/iwouldrathercode/cognito.svg?style=flat-square)](https://packagist.org/packages/iwouldrathercode/cognito)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/iwouldrathercode/cognito/run-tests?label=tests)](https://github.com/iwouldrathercode/cognito/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/iwouldrathercode/cognito/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/iwouldrathercode/cognito/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/iwouldrathercode/cognito.svg?style=flat-square)](https://packagist.org/packages/iwouldrathercode/cognito)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation & Setup

### Step 1
You can install the package via composer:
```bash
composer require iwouldrathercode/cognito
```
### Step 2
Run the setup command to install dependencies and link the storage folder to public folder
```bash
php artisan cognito:setup
```
### Step 3
Run the install command to publish the config files and migrations
```bash
php artisan cognito:install
```
### Step 4
Execute the migrations. This will update the users table and since passwords are no longer managed by laravel, this will also delete the password_resets table
```bash
php artisan migrate
```
### Step 5
Update the config/auth.php with default guard as `api`
```php
    ..
    ..

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    ..
    ..
```
### Step 6
Update the config/auth.php, guards array with config of `api` using 'cognito' as driver
```php
    ..
    ..
    'guards' => [
        ..
        ..

        'api' => [
            'driver' => 'cognito',
            'provider' => 'users'
        ]
    ],
    ..
    ..
```
### Step 7
Ensure the env variables of the cognito UserPool and ClientID are at .env this will affect `congig/cognito.php` config file
```bash
AWS_COGNITO_USER_POOL_ID=
AWS_COGNITO_CLIENT_ID=
AWS_DEFAULT_REGION=
```
## Usage
This package will create /api routes to manage authentication
```php
POST -> api/confirm-forgot-password -> confirm-forgot-password › 
Iwouldrathercode\Cognito\Http\Controllers\SelfServiceController@confirmForgotPassword

POST -> api/forgot-password -> forgot-password › Iwouldrathercode\Cognito\Http\Controllers\SelfServiceController@forgotPassword

POST -> api/login -> signin › 
Iwouldrathercode\Cognito\Http\Controllers\LoginController@login

POST -> api/register -> signup › 
Iwouldrathercode\Cognito\Http\Controllers\RegisterController@register

POST -> api/verify -> verify › 
Iwouldrathercode\Cognito\Http\Controllers\EmailVerificationController@verify
```
## Testing
```bash
composer test
```
## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
## Credits
- [Shankar](https://github.com/psgganesh)
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
