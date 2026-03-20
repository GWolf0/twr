# TWR (Two Wheeler Renting) Demo, Docker Setup Instructions

## Requirements

- Docker
- Docker Compose

---

# Steps
1. Create ".env" based on ".env.example"
```
cp .env.example .env
```
2. Generate the app key
```
php artisan key:generate
```
3. Run containers
```
docker compose up -d --build
```
4. Optional seed
```
docker compose exec app php artisan db:seed
```
