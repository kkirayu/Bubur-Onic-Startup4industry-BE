# Bubur Onic API
Bubur Onic API merupakan aplikasi rest api yang digunakan untuk memberikan API untuk aplikasi yang membutuhkan data bubur onic.

## Required Instalasi
1. PHP 8.2
1. Composer
1. PostgreSQL

## Setup Local
1. Clone Repository `git clone https://gitlab.javan.co.id/alurkerja/projects/bubur-onic-be.git`
1. Salin .env.example ke .env
1. Konfigurasi DB sesuai dengan konfig
1. Jalankan `composer install`
1. Jalankan `php artisan key:generate`
1. Jalankan `php artisan storage:link`
1. Jalankan `php artisan migrate`
1. Jalankan `php artisan serve`
1. Buka browser `localhost:8000`
