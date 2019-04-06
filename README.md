# Auth

[![Coverage report](http://gitlab.awescode.com/awes-io/auth/badges/master/coverage.svg)](https://www.awes.io/)
[![Build status](http://gitlab.awescode.com/awes-io/auth/badges/master/build.svg)](https://www.awes.io/)
[![Composer Ready](https://www.awc.wtf/awes-io/auth/status.svg)](https://www.awes.io/)
[![Downloads](https://www.awc.wtf/awes-io/auth/downloads.svg)](https://www.awes.io/)
[![Last version](https://www.awc.wtf/awes-io/auth/version.svg)](https://www.awes.io/) 

Laravel AWES.IO Auth package. Take a look at [contributing.md](contributing.md) to see a to do list..

## Installation

Via Composer

``` bash
$ composer require awes-io/auth
```

The package will automatically register itself.

You can publish migrations with:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="migrations"
```

After migrations have been published you can create required tables by running:

```bash
php artisan migrate
```

And seed countries table:

```bash
php artisan db:seed --class="AwesIO\Auth\Seeds\CountryTableSeeder"
```

You can publish config file with:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="config"
```

You can publish views with:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="views"
```

## Configuration

You can disable some of package's additional features:

```php
'enabled' => [
    'social', 
    // 'two_factor',
],
```

Add new socialite services:

```php
'services' => [
    'github' => [
        'name' => 'GitHub'
    ],
    ...
],
'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    ...
],
```

Configure redirects on package's routes:

```php
'redirects' => [
    'login' => '/twofactor',
    'reset_password' => '/',
    ...
],
```

Several .env variables required if additional modules were enabled in config:

```php
# SOCIALITE GITHUB
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URL=http://auth.test/login/github/callback

# 2FA AUTHY
AUTHY_SECRET=
```

If you enabled social and/or two factor authentication add respective traits to User model class:

```php
use AwesIO\Auth\Models\Traits\HasSocialAuthentication;
use AwesIO\Auth\Models\Traits\HasTwoFactorAuthentication;

class User extends Authenticatable
{
    use HasSocialAuthentication, HasTwoFactorAuthentication;
}
```

### Email verification

To use email verification functionality, add SendsEmailVerification trait:

```php
use AwesIO\Auth\Models\Traits\SendsEmailVerification;

class User extends Authenticatable
{
    use SendsEmailVerification;
}
```

and make sure to enable it in awesio-auth.php config file:

```php
'enabled' => [
    ...
    'email_verification',
    ...
],
```

## Usage

Add to routes/web.php:

```php
AwesAuth::routes();
```

By default package will register several routes:

```
+--------+----------------------------------------+--------------------------+--------------------------+----------------------------------------------------------------------+--------------------------------------------------------+
| Domain | Method                                 | URI                      | Name                     | Action                                                               | Middleware                                             |
+--------+----------------------------------------+--------------------------+--------------------------+----------------------------------------------------------------------+--------------------------------------------------------+
|        | GET|HEAD                               | email/resend             | verification.resend      | AwesIO\Auth\Controllers\VerificationController@resend                | web,auth,throttle:6,1                                  |
|        | POST                                   | email/verify             | verification.code.verify | AwesIO\Auth\Controllers\VerificationController@verifyCode            | web,auth                                               |
|        | GET|HEAD                               | email/verify             | verification.code        | AwesIO\Auth\Controllers\VerificationController@show                  | web,auth                                               |
|        | GET|HEAD                               | email/verify/{id}        | verification.verify      | AwesIO\Auth\Controllers\VerificationController@verify                | web,auth,signed,throttle:6,1                           |
|        | POST                                   | login                    |                          | AwesIO\Auth\Controllers\LoginController@login                        | web,guest                                              |
|        | GET|HEAD                               | login                    | login                    | AwesIO\Auth\Controllers\LoginController@showLoginForm                | web,guest                                              |
|        | POST                                   | login/twofactor/verify   | login.twofactor.verify   | AwesIO\Auth\Controllers\TwoFactorLoginController@verify              | web,guest                                              |
|        | GET|HEAD                               | login/twofactor/verify   | login.twofactor.index    | AwesIO\Auth\Controllers\TwoFactorLoginController@index               | web,guest                                              |
|        | GET|HEAD                               | login/{service}          | login.social             | AwesIO\Auth\Controllers\SocialLoginController@redirect               | web,guest,AwesIO\Auth\Middlewares\SocialAuthentication |
|        | GET|HEAD                               | login/{service}/callback |                          | AwesIO\Auth\Controllers\SocialLoginController@callback               | web,guest,AwesIO\Auth\Middlewares\SocialAuthentication |
|        | GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS | logout                   | logout                   | AwesIO\Auth\Controllers\LoginController@logout                       | web                                                    |
|        | POST                                   | password/email           | password.email           | AwesIO\Auth\Controllers\ForgotPasswordController@sendResetLinkEmail  | web,guest                                              |
|        | POST                                   | password/reset           | password.update          | AwesIO\Auth\Controllers\ResetPasswordController@reset                | web,guest                                              |
|        | GET|HEAD                               | password/reset           | password.request         | AwesIO\Auth\Controllers\ForgotPasswordController@showLinkRequestForm | web,guest                                              |
|        | GET|HEAD                               | password/reset/{token}   | password.reset           | AwesIO\Auth\Controllers\ResetPasswordController@showResetForm        | web,guest                                              |
|        | POST                                   | register                 |                          | AwesIO\Auth\Controllers\RegisterController@register                  | web,guest                                              |
|        | GET|HEAD                               | register                 | register                 | AwesIO\Auth\Controllers\RegisterController@showRegistrationForm      | web,guest                                              |
|        | GET|HEAD                               | twofactor                | twofactor.index          | AwesIO\Auth\Controllers\TwoFactorController@index                    | web,auth                                               |
|        | POST                                   | twofactor                | twofactor.store          | AwesIO\Auth\Controllers\TwoFactorController@store                    | web,auth                                               |
|        | DELETE                                 | twofactor                | twofactor.destroy        | AwesIO\Auth\Controllers\TwoFactorController@destroy                  | web,auth                                               |
|        | POST                                   | twofactor/verify         | twofactor.verify         | AwesIO\Auth\Controllers\TwoFactorController@verify                   | web,auth                                               |
+--------+----------------------------------------+--------------------------+--------------------------+----------------------------------------------------------------------+--------------------------------------------------------+
```

Besides standard authentication laravel routes, package will add:

```php
# Socialite routes
'login.social'
'login/{service}/callback'

# 2FA setup and routes for enabling/disabling it
'twofactor.index', 'twofactor.store', 'twofactor.destroy', 'twofactor.verify'

# 2FA login routes
'login.twofactor.index', 'login.twofactor.verify'

# Email verification routes
'verification.resend', 'verification.code.verify', 'verification.code', 'verification.verify'
```

## Testing

You can run the tests with:

```bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [:author_name][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v3.0. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/awes-io/auth.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/awes-io/auth.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/awes-io/auth/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/awes-io/auth
[link-downloads]: https://packagist.org/packages/awes-io/auth
[link-travis]: https://travis-ci.org/awes-io/auth
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/awes-io
[link-contributors]: ../../contributors]
