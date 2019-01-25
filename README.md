# Auth

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

[![Coverage report](http://gitlab.awescode.com/awes-io/auth/badges/master/coverage.svg)](https://www.awes.io/)
[![Build status](http://gitlab.awescode.com/awes-io/auth/badges/master/build.svg)](https://www.awes.io/)
[![Composer Ready](https://www.awc.wtf/awes-io/auth/status.svg)](https://www.awes.io/)
[![Downloads](https://www.awc.wtf/awes-io/auth/downloads.svg)](https://www.awes.io/)
[![Last version](https://www.awc.wtf/awes-io/auth/version.svg)](https://www.awes.io/) 


This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

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

You can publish config file with:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="config"
```

You can publish views with:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="views"
```

## Examples of use

Add to routes/web.php:

```php
AwesAuth::routes();
```

By default package will register several routes:

```
+--------+----------+--------------------------+------------------------+----------------------------------------------------------------------+--------------------------------------------------------+
| Domain | Method   | URI                      | Name                   | Action                                                               | Middleware                                             |
+--------+----------+--------------------------+------------------------+----------------------------------------------------------------------+--------------------------------------------------------+
|        | GET|HEAD | login                    | login                  | AwesIO\Auth\Controllers\LoginController@showLoginForm                | web,guest                                              |
|        | POST     | login                    |                        | AwesIO\Auth\Controllers\LoginController@login                        | web,guest                                              |
|        | GET|HEAD | login/twofactor/verify   | login.twofactor.index  | AwesIO\Auth\Controllers\TwoFactorLoginController@index               | web,guest                                              |
|        | POST     | login/twofactor/verify   | login.twofactor.verify | AwesIO\Auth\Controllers\TwoFactorLoginController@verify              | web,guest                                              |
|        | GET|HEAD | login/{service}          | login.social           | AwesIO\Auth\Controllers\SocialLoginController@redirect               | web,guest,AwesIO\Auth\Middlewares\SocialAuthentication |
|        | GET|HEAD | login/{service}/callback |                        | AwesIO\Auth\Controllers\SocialLoginController@callback               | web,guest,AwesIO\Auth\Middlewares\SocialAuthentication |
|        | POST     | logout                   | logout                 | AwesIO\Auth\Controllers\LoginController@logout                       | web                                                    |
|        | POST     | password/email           | password.email         | AwesIO\Auth\Controllers\ForgotPasswordController@sendResetLinkEmail  | web,guest                                              |
|        | GET|HEAD | password/reset           | password.request       | AwesIO\Auth\Controllers\ForgotPasswordController@showLinkRequestForm | web,guest                                              |
|        | POST     | password/reset           | password.update        | AwesIO\Auth\Controllers\ResetPasswordController@reset                | web,guest                                              |
|        | GET|HEAD | password/reset/{token}   | password.reset         | AwesIO\Auth\Controllers\ResetPasswordController@showResetForm        | web,guest                                              |
|        | POST     | register                 |                        | AwesIO\Auth\Controllers\RegisterController@register                  | web,guest                                              |
|        | GET|HEAD | register                 | register               | AwesIO\Auth\Controllers\RegisterController@showRegistrationForm      | web,guest                                              |
|        | GET|HEAD | twofactor                | twofactor.index        | AwesIO\Auth\Controllers\TwoFactorController@index                    | web,auth                                               |
|        | POST     | twofactor                | twofactor.store        | AwesIO\Auth\Controllers\TwoFactorController@store                    | web,auth                                               |
|        | DELETE   | twofactor                | twofactor.destroy      | AwesIO\Auth\Controllers\TwoFactorController@destroy                  | web,auth                                               |
|        | POST     | twofactor/verify         | twofactor.verify       | AwesIO\Auth\Controllers\TwoFactorController@verify                   | web,auth                                               |
+--------+----------+--------------------------+------------------------+----------------------------------------------------------------------+--------------------------------------------------------+
```


## Methods

#### example()

Description some example.

#### count()

Description some count.

#### validate(string $email)

Throws an `InvalidArgumentException` is email is invalid.

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
