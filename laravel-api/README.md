### Installation


1. Install dependencies

````
composer install
````

2. Copy .env file

```
cp .env.example .env
```

3. Modify `DB_*` value in `.env` with your database config.

4. Generate application key:

````
php artisan key:generate
````

5. Migrate
````
php artisan migrate
````
6. Create Personal Access
````
php artisan passport:client --personal
````

7. Run
````
php artisan serve
````