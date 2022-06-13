init:  docker-up app-start
start: docker-up
restart: docker-down docker-up
down: docker-down
migrate: app-migrate
rollback: app-rollback

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

app-migrate:
	docker-compose exec php-fpm php artisan migrate

app-rollback:
	docker-compose exec php-fpm php artisan migrate:rollback

app-start:
	docker-compose exec php-fpm composer install
	docker-compose exec php-fpm php artisan key:generate
	docker-compose exec php-fpm php artisan cache:clear
	docker-compose exec php-fpm php artisan migrate
	docker-compose exec php-fpm php artisan db:seed
	docker-compose restart

permissions:
	chmod -R 775 storage
	chmod -R 775 bootstrap/cache
	chmod -R 777 storage/logs/

cache-clear:
	docker-compose exec  php-fpm php artisan cache:clear
	docker-compose exec  php-fpm php artisan view:clear
	docker-compose exec  php-fpm php artisan route:clear
	docker-compose exec  php-fpm php artisan config:cache
