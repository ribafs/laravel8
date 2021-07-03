# Dashboard para laravel 8 usando JetStream e Tailwind

https://github.com/miten5/larawind

# Clone the repository from GitHub and open the directory:
git clone https://github.com/miten5/larawind.git

# cd into your project directory
cd larawind

# install composer and npm packages
composer install
npm install && npm run dev

# Start prepare the environment:
cp .env.example .env // setup database credentials
php artisan key:generate
php artisan migrate
php artisan storage:link

# Run your server
php artisan serve

http://localhost:8000/register

