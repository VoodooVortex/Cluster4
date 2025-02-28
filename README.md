<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 🚀 First-time repository setup

Follow these steps to set up the project for the first time:

1. **Clone this repository to your local machine:**
```bash
git clone https://github.com/OSSD13/cluster4.git
```

2. **Go into the project directory:**
```bash
cd project-name
```

3. **Install dependencies:**
*** If you're cloning this project for the first time, run this command: ***
```bash
composer install
```

*** If someone adds new packages after you pull, you need to run this command: ***
```bash
composer update
```

4. **Configure the .env file:**
```bash
cp .env.example .env
```

5. **Generate the application key:**
```bash
php artisan key:generate
```

7. **Make sure the database configuration in the .env file is correct.**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cluster_4
DB_USERNAME=root
DB_PASSWORD=your_password
```

8. **Run database migrations:**
```bash
php artisan migrate
```

## Requirements

- PHP 8.2+
- Composer
- Laravel 11
- FontAwesome 6.7.2

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
