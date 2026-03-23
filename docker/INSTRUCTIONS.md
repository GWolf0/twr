# TWR (Two Wheeler Renting) Demo, Docker Setup Instructions

## Requirements

- Docker
- Docker Compose

---

## Steps

1. Create ".env" based on ".env.example":
```
cp .env.example .env
```
2. Change env values:
- APP_NAME (e.g., TWR)
- APP_ENV=production
- APP_DEBUG=false
- APP_URL (set a custom URL or keep localhost)
- DB_CONNECTION=sqlite
- DB_DATABASE=database/database.sqlite
3. Create required files:
- .env: already created in Step 1
- SQLite database: /database/database.sqlite
- Image storage folder: /storage/app/public/images
```
touch database/database.sqlite
mkdir -p storage/app/public/images
```
4. Install php and javascript dependencies:
```
composer install
npm install
npm run build
```
5. Generate app key:
```
php artisan key:generate
```
6. Run containers:
```
docker compose up --build
```
7. Link storage:
```
php artisan storage:link
```
8. Run migrations (and seed):
```
php artisan migrate:fresh --seed
```

---

## Notes:

- Make sure your local user has proper permissions for files created inside the container (e.g., using user: "1000:1000" in docker-compose.yml).
    - Use the id command to get your local user ID and group ID, and match them in the container configuration.
    - This prevents files created by the container from having higher privileges than your local user.
    - If you have root access on your local machine, this may not be an issue.
- Generate the application key before running containers to avoid issues where environment variables are not properly picked up.
- Ensure APP_URL is correctly set if you are not using localhost.
