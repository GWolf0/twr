# TWR (Two Wheeler Renting) Demo

## Requirements

- Docker
- Docker Compose

---

# Run the project

docker compose up -d --build

---

# Setup Laravel

docker compose exec app php artisan key:generate
docker compose exec app php artisan config:clear
docker compose exec app php artisan storage:link
docker compose exec app php artisan migrate:fresh --seed
