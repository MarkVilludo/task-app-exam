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
   # Edit .env and set your database and app settings
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
