composer install
npm install
npm run prod
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
