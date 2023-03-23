# Installation

```bash
composer require vkoori/env-reloader
```

# Doc

This package causes the code inside the .env file to be rewritten. After changing the .env, the configuration of the application will also be updated.

# Sample

```
dump(env('APP_ENV'));
Reload::env(
    data: [
        'APP_ENV' => 'production'
    ]
);
dump(env('APP_ENV'));
```
