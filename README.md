## Pre-Requisites

This project is built on top of a modern Laravel boilerplate, leveraging Jetstream, Inertia.js, and my package for admin panel for rapid development and best practices.

**Key Boilerplate Packages Used:**
- [Laravel 12](https://laravel.com/) (PHP framework)
- [mark-villudo/laravel-admin-panel-inertia-vue](https://packagist.org/packages/mark-villudo/laravel-admin-panel-inertia-vue) (custom admin panel boilerplate)
- [lorisleiva/laravel-actions](https://github.com/lorisleiva/laravel-actions) (action classes)
- [laravel/sanctum](https://laravel.com/docs/10.x/sanctum) (API authentication)

**System Requirements:**
```sh
php 8.2.28
nvm v20
npm 11.4.2
node v20.19.3
laravel 12
```

## Installation & Setup

1. **Clone the repository**
   ```sh
   git clone https://github.com/MarkVilludo/task-app-exam
   cd task-app-exam
   ```

2. **Install PHP dependencies**
   ```sh
   composer install
   ```

3. **Install Node.js dependencies**
   ```sh
   npm install
   ```

4. **Copy and configure your environment file**
   ```sh
   cp .env.example .env
   # Edit .env and set your database and app settings (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.)
   ```

5. **Generate application key**
   ```sh
   php artisan key:generate
   ```

6. **Run migrations**
   ```sh
   php artisan migrate
   ```

7. **Create storage symlink for images**
   ```sh
   php artisan storage:link
   ```

8. **(Optional) Seed the database**
   ```sh
   php artisan db:seed
   ```

9. **Start the development servers**
   - For backend (Laravel):
     ```sh
     php artisan serve
     ```
   - For frontend (Vite):
     ```sh
     npm run dev
     ```

10. **Login/Register and access the app at** [http://localhost:8000/tasks](http://localhost:8000/tasks)

### Required Composer Packages
- [lorisleiva/laravel-actions](https://github.com/lorisleiva/laravel-actions)
- [inertiajs/inertia-laravel](https://inertiajs.com/)
- [laravel/jetstream](https://jetstream.laravel.com/) (if using Jetstream)
- [laravel/sanctum](https://laravel.com/docs/10.x/sanctum) (for API auth)

### Required Node Packages
- [vue@3](https://vuejs.org/)
- [@inertiajs/inertia-vue3](https://inertiajs.com/)
- [axios](https://axios-http.com/)
- [vite](https://vitejs.dev/)

---

## Code Quality: Static Analysis with PHPStan

This project uses [PHPStan](https://phpstan.org/) for static analysis and code quality checks.

To run PHPStan:
```sh
vendor/bin/phpstan analyse app database routes --memory-limit=512M
```
A successful run will show: `[OK] No Errors`


## Auto Remove trash

The number of days is configurable in `config/task.php`

To run cleanup trash:
```sh
php artisan app:delete-old-trashed-tasks
```
