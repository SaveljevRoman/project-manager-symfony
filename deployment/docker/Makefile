# Все комананды make запускаем изи дирректории ../deployment/docker

# Пересобираем проект при необходимости проект
init: down-clear build up ps

# Останавливаем контенеры
stop:
	docker-compose stop

# Опускаем контейнеры down и удаляем контейнера для неопределенных сервисов
down:
	docker-compose down --remove-orphans

# Опускаем контейнеры down и удаляем контейнера для неопределенных сервисов и volumes
down-clear:
	docker-compose down -v --remove-orphans

# Собираем проект
build:
	docker-compose build

# Поднимаем контейнеры
up:
	docker-compose up -d

ps:
	docker-compose ps

