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

### Remove package from vendor this commond
```
composer remove laravel/reverb
```

## install brodcasting
```
php artisan install:broadcasting
```

7. Start development server
```
php artisan serve
npm run dev:tailwind
php artisan reverb:start
php artisan queue:work
```

8. Firebase Notification package
```
composer require kreait/firebase-php
```

