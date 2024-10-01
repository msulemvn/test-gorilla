# test-gorilla
## Project Configuration

### Timezone
The project timezone is set to `Asia/Karachi`.

### PHP Settings
To accommodate larger file uploads, the following PHP settings have been adjusted:

### Configure Auth Guard
Inside the config/auth.php file you will need to make a few changes to configure Laravel to use the jwt guard to power your application authentication.
Make the following changes to the file:

```config/auth.php
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
Here we are telling the api guard to use the jwt driver, and we are setting the api guard as the default.
 We can now use Laravel's built-in Auth system, with jwt-auth doing the work behind the scenes!

```
cd laravel-jwt
```


