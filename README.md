## Installation:

1. Clone the repository:
```
git clone https://github.com/Sarman-Dasa/laravel-vue-chat-app.git
```

2. Navigate to the project directory:
```
cd laravel-vue-chat-app
```

3. Install dependencies:
```
composer install
npm install
```

4. Generate application key:
```
php artisan key:generate
```

5. Configure database connection:

Edit `.env` file according to your database credentials.

6. Migrate database tables
```
php artisan migrate
```

7. Start development server
```
php artisan serve
npm run dev:tailwind
npm install --save-dev laravel-echo
php artisan reverb:start
php artisan queue:work
```