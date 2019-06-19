<h1 align="center">Authentication</h1>

<p align="center">Laravel Authentication package with built-in two-factor (Authy) and social authentication (Socialite).</p>

## Table of Contents

- <a href="#installation">Installation</a>
- <a href="#configuration">Configuration</a>
    - <a href="#social-and-two-factor-authentication">Social and two-factor authentication</a>
    - <a href="#email-verification-resetting-passwords">Email verification & resetting passwords</a>
- <a href="#usage">Usage</a>
- <a href="#testing">Testing</a>

## Installation

Via Composer

``` bash
$ composer require awes-io/auth
```

The package will automatically register itself.

You can publish migrations:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="migrations"
```

After migrations have been published you can create required db tables by running:

```bash
php artisan migrate
```

Publish views:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="views"
```

## Configuration

Publish config file:

```bash
php artisan vendor:publish --provider="AwesIO\Auth\AuthServiceProvider" --tag="config"
```

You can disable additional features by commenting them out:

```php
'enabled' => [
    'social', 
    // 'two_factor',
    // 'email_verification',
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

And configure redirect paths:

```php
'redirects' => [
    'login' => '/twofactor',
    'reset_password' => '/',
    ...
],
```

### Social and two-factor authentication

Several .env variables required if additional modules were enabled in config:

```php
# SOCIALITE GITHUB
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URL=http://auth.test/login/github/callback

# TWO FACTOR AUTHY
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

### Email verification & resetting passwords

To use email verification functionality and to reset passwords, add `SendsEmailVerification` and `SendsPasswordReset` traits:

```php
use AwesIO\Auth\Models\Traits\SendsPasswordReset;
use AwesIO\Auth\Models\Traits\SendsEmailVerification;

class User extends Authenticatable
{
    use SendsEmailVerification, SendsPasswordReset;
}
```

## Usage

Add to routes/web.php:

```php
AwesAuth::routes();
```

You can disable registration:

```php
AwesAuth::routes(['register' => false]);
```

Package will register several routes.

##### Besides default authentication routes, it will add:
* Socialite routes
    * `'login.social'`
    * `'login/{service}/callback'`
* Two factor authentication setup routes
    * `'twofactor.index'`
    * `'twofactor.store'`
    * `'twofactor.destroy'`
    * `'twofactor.verify'`
* Two factor authentication login routes
    * `'login.twofactor.index'`
    * `'login.twofactor.verify'`
* Email verification routes
    * `'verification.resend'`
    * `'verification.code.verify'`
    * `'verification.code'`
    * `'verification.verify'`

## Testing

You can run the tests with:

```bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Credits

- [Galymzhan Begimov](https://github.com/begimov)
- [All Contributors](contributing.md)

## License

[MIT](http://opensource.org/licenses/MIT)
