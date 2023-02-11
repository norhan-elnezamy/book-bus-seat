# Bus Booking System


## Installation and configuration steps

```bash
git clone https://gitlab.com/norhanelnezamy/bus-booking.git
```

```bash
cd bus-booking
```

```bash
cp -i .env.example .env
```
```bash
php artisan key:generate
```

Please note to set database connection in .env file 

```bash
composer install
```

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

```bash
php artisan serve
```

## Usage

Use [Landing](http://127.0.0.1:8000) to landing page.

## Unit Test Configuration

```bash
touch /storage/database.sqlite
```

```bash
php artisan migrate --database=sqlite
```

```bash
 ./vendor/bin/phpunit --filter=TripTest
```



