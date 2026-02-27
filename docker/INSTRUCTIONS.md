- Step 1:
    - docker compose up -d --build

___

- Step 2:
    - docker exec -it twr_app bash
    - composer install
    - php artisan key:generate
    - php artisan migrate

___
- Step 3:
    - check: http://localhost:8000
    - phpmyadmin: http://localhost:8080
    - mailhog: http://localhost:8025

___
- Notes for production:
    - Remove: phpMyAdmin, Mailhog, Port 3306 exposed
    - Only expose port 80/443

