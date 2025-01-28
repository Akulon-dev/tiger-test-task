# Тестовое задание для Tiger

## Развёртывание

```bash
git clone https://github.com/Akulon-dev/tiger-test-task.git
cd tiger-test-task
cp .env.production .env
composer install --no-dev --optimize-autoloader
php artisan key:generate

docker compose build
docker compose up -d

docker exec -it tiger-test-task php artisan l5-swagger:generate
docker exec -it tiger-test-task php artisan config:cache
docker exec -it tiger-test-task php artisan route:cache
```

## Документация
доступна по адресу **/api/documentation**. Там же можно попробовать методы API.


