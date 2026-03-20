# TWR

## App Name
**TWR (Two-Wheeler Renting)** - temporary project name.

---

## Index

- [Description](#description)
- [Features](#features)
- [Stack](#stack)
- [Screenshots](#screenshots)
- [Getting Started](#getting-started)
- [Models](#models)
- [Model Relationships](#model-relationships)
- [Project Assumptions](#project-assumptions)
- [Non-goals](#non-goals)
- [Notes](#notes)

---

## Description

TWR is a **two-wheeler rental management application** designed to manage vehicle listings, bookings, users, and administrative operations.

The system provides a clear separation between **client and administrator roles** and focuses on delivering a structured and maintainable rental workflow.

The project emphasizes **clean architecture, maintainability, and API-driven design** while implementing a realistic rental management flow.

---

## Features

- Role-based users (Admin, Client)
- Clients can browse available vehicles
- Clients can book vehicles (subject to availability conditions)
- Clients can view their bookings
- Clients can cancel bookings
- Administrators can manage vehicles (CRUD)
- Administrators can manage users (CRUD)
- Administrators can manage bookings (CRUD)
- Booking invoice generation (rental summary / receipt)
- Multi-language support (Partial)

---

## Stack

- **Backend:** Laravel
- **Frontend:** Blade, JavaScript
- **Database:** MySQL / SQLite
- **Styling:** TailwindCSS

---

## Screenshots

![Alt text](public/screenshots/twr%20(2).png)
![Alt text](public/screenshots/twr%20(3).png)
![Alt text](public/screenshots/twr%20(4).png)
![Alt text](public/screenshots/twr%20(5).png)
![Alt text](public/screenshots/twr%20(6).png)

---

## Getting Started

### Local Setup

1. Clone the repository
    - git clone [https://github.com/GWolf0/twr.git](https://github.com/GWolf0/twr.git)
    - cd twr

2. Install PHP dependencies
    - composer install

3. Setup environment file
    - cp .env.example .env

4. Generate app key
    - php artisan key:generate

5. Create SQLite database
    - touch database/database.sqlite

6. Configure .env (SQLite)
- Make sure these values are set:
    - DB_CONNECTION=sqlite
    - DB_DATABASE=database/database.sqlite

7. Run migrations & seeders
    - php artisan migrate --seed

8. Storage symlink
    - php artisan storage:link

9. Run dev server
    - composer run dev

### Docker

Refer to "/docker/INSTRUCTIONS.md".

---

## Models
- User (users)
    - id
    - name
    - email
    - password
    - role (admin, customer) — default: customer
    - created_at
    - updated_at

- Vehicle (vehicles)
    - id
    - name
    - type
    - media (array of media IDs)
    - price_per_hour
    - availability (available, unavailable, maintenance) — default: available
    - created_at
    - updated_at

- Booking (bookings)
    - id
    - user_id
    - vehicle_id
    - start_date
    - end_date
    - status (pending, confirmed, canceled, completed) — default: pending
    - payment_status (unpaid, paid, refunded) — default: unpaid
    - payment_method (cash, credit_card, other) — default: cash
    - total_amount
    - created_at
    - updated_at

- Media (media)
    - id
    - type (image, video)
    - url
    - size
    - user_id (typically the admin uploader)
    - created_at
    - updated_at

- Setting (settings)
    A single record storing application configuration.
    - id
    - business_name
    - business_description
    - business_phone_number
    - business_addresses (CSV format)
    - created_at
    - updated_at

---

## Model Relationships

- Booking
    - belongsTo User
    - belongsTo Vehicle

- Vehicle
    - hasMany Booking

- User
    - hasMany Booking

---

## Project Assumptions

### API-first design
The application follows an API-first architecture:
- RESTful APIs are defined for all operations (authentication, CRUD, etc.)
- Integration tests focus primarily on API behavior through controllers

### ID Obfuscation
- Public identifiers use Hashids for ID obfuscation
- This generates UUID-like public identifiers derived from internal numeric IDs

### Project Structure
- Interfaces > /app/Interfaces
- Helper functions > /app/Helpers
- Services > /app/Services
- Enums > /app/Misc/Enums
- Project configuration > /config/twr.php

### Model Requirements
Each model must include:
- CRUD controller
- Factory
- Policy implementation

### Multi-language Support
The application is designed to support multiple languages through Laravel localization.

### Testing
Testing focuses primarily on integration tests.
Tests verify API behavior rather than individual components.

---

## UI Design

Visual theme:
- Beige background
- Purple accent color

Mascot concept:
- White rabbit
- Purple scarf
- Riding a red scooter

---

## Non-goals

The project intentionally excludes the following features:
- Real payment gateway integration (payments are mocked or manual)
- Real-time vehicle availability synchronization
- Mobile application

---

## Notes

Real-world rental management systems typically require extensive domain research, detailed specifications, and business consultation.

This project intentionally takes a high-level approach, focusing primarily on software architecture, implementation quality, and maintainable backend design from a web developer’s perspective.  

Some details in this README may not exactly match the final implementation as the project evolved during development.

Bugs are expected.

Approx dev duration: 2 months (01-2026, 03-2026)