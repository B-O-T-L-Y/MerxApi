build:
	docker compose up -d --build

up:
	docker compose up -d

down:
	docker compose down

back:
	docker compose exec app sh

install:
	docker compose exec app sh -c "composer install"

migrate:
	docker compose exec app php scripts/migrate.php

seed:
	docker compose exec app php scripts/seed.php