$ docker-compose up -d

$ docker-compose exec php symfony composer install

$ docker-compose exec php symfony console d:m:m -n

$ docker-compose exec php symfony console doctrine:fixtures:load --group=dev