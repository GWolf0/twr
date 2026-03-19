# TWR (Two Wheeler Renting) Demo, Docker Setup Instructions

## Requirements

- Docker
- Docker Compose

---

## Notes
- Generating the APP_KEY in or after the building phase doesn't reflect on laravel, so to avoid the struggle, we generate the key before building the containers.

# Steps
- Create ".env.docker" based on ".env.example"
- Generate the app key in ".env.docker" (the env file being copied into the container)
- Run containers: docker compose up -d --build
- Optional seed: docker compose exec app php artisan db:seed
