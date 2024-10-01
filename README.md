# test-gorilla

<p align="center">
    <a href="build_status_url">
        <img src="build_status_image_url" alt="Build Status">
    </a>
    <a href="total_downloads_url">
        <img src="total_downloads_image_url" alt="Total Downloads">
    </a>
    <a href="latest_stable_version_url">
        <img src="latest_stable_version_image_url" alt="Latest Stable Version">
    </a>
    <a href="license_url">
        <img src="license_image_url" alt="License">
    </a>
</p>

## Project Configuration

### Timezone
The project timezone is set to `Asia/Karachi`.

### PHP Settings
To accommodate larger file uploads, the following PHP settings have been adjusted:

```ini
post_max_size = 2G
upload_max_filesize = 2G
``
``
composer create-project laravel/laravel laravel-jwt
composer require php-open-source-saver/jwt-auth
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
``

### Configure Auth Guard
Inside the config/auth.php file you will need to make a few changes to configure Laravel to use the jwt guard to power your application authentication.
Make the following changes to the file:

``
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

``
Here we are telling the api guard to use the jwt driver, and we are setting the api guard as the default.
 We can now use Laravel's built-in Auth system, with jwt-auth doing the work behind the scenes!

``
cd laravel-jwt


