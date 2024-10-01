# test-gorilla
## Project Configuration

### Timezone
In App\config\app.php project timezone is set to 
```
'timezone' => 'Asia/Karachi',
```
### PHP Settings
To accommodate larger file uploads, the following PHP settings have been adjusted:
```
post_max_size = 2G
upload_max_filesize = 2G
```
###  Install JWT Auth Package 
```
composer require php-open-source-saver/jwt-auth
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```
### Configure Auth Guard
Inside the config/auth.php file you will need to make a few changes to configure Laravel to use the jwt guard to power your application authentication.
Make the following changes to the file:
In App/config/auth.php
```
'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],


    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
                'driver' => 'jwt',
                'provider' => 'users',
        ],

    ],

```
