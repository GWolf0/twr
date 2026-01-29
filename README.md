# TWR

## App name
TWR (two-wheeler renting), temp name

## Index
- [description](#description)
- [features](#features)
- [stack](#stack)
- [getting started](#getting-started)
- [pages](#pages)
- [models](#models)
- [project assumptions](#project-assumptions)
- [non goals](#non-goals)
- [notes](#notes)

## Description
TWR is a two-wheeler rental management application designed to handle vehicle listings, bookings, users, and administrative operations.
The system provides a clear separation between client and admin roles and focuses on delivering a structured, maintainable rental workflow.

## Features
- Role-bound users (admin, client)
- Client can browse vehicles
- Client can book a vehicle (if certain conditions are met)
- Client can view his bookings
- Client can cancel a booking
- Admin can CRUD vehicles
- Admin can CRUD users
- Admin can CRUD bookings
- Client and admin can view a booking's invoice (receipt/simple rental summary)
- Multi-language support

## Stack
- Laravel (blade, front-end and back-end)
- MySQL (DBMS)
- TailwindCSS (Styling)

## Getting Started
- cp .env.example .env
- php artisan key:generate
- php artisan migrate --seed
- php artisan serve

## Pages
- Home page: /
- Login page: /login (guest)
- Register page: /register (guest)
- Password reset notification sending: /password-reset-notification
- Password reset: /password-reset
- Email confirmation: /email-confirmation
- Search page: /search?query..
- Book page: /book/{vehicle_uuid}
- Booking confirmation page: /book/{vehicle_uuid}/confirmation (auth:client)
- My bookings page: /bookings (auth:client)
- My profile page: /profile (auth:client)
- Booking invoice page: /vehicles/{vehicle_uuid}/invoice (auth:client if owner | auth:admin)
- Admin dashboard: /dashboard (auth:admin, defaults to stats page)
- Admin dashboard stats: /dashboard/stats (auth:admin)
- Admin dashboard vehicles list and delete: /dashboard/models/vehicles (auth:admin)
- Admin dashboard vehicles new record: /dashboard/models/vehicles/new (auth:admin)
- Admin dashboard vehicles edit record: /dashboard/models/vehicles/edit/{vehicle_uuid} (auth:admin)
- Admin dashboard (same crud as above for all other models)
- Admin dashboard settings: /dashboard/settings (auth:admin)

## Models
- User (users):
    - id
    - name
    - email
    - password
    - role (admin, customer), default=customer
    - created_at
    - updated_at
- Vehicle (vehicles):
    - id
    - name
    - type
    - media (array of media ids)
    - price_per_hour
    - availability (available, unavailable, maintenance), default=available
    - created_at
    - updated_at
- Booking (bookings):
    - id
    - user_id
    - vehicle_id
    - start_date
    - end_date
    - status (pending, confirmed, canceled, completed), default=pending
    - payment_status (unpaid, paid, refunded), default=unpaid
    - payment_method (cash, credit_card, other), default=cash
    - total_amount
    - created_at
    - updated_at
- Media (media):
    - id
    - type (image, video)
    - url
    - size
    - user_id (admin id may be used)
    - created_at
    - updated_at
- Setting (settings, one record/instance holding project settings):
    - id
    - business_name
    - business_description
    - business_phone_number
    - business_addresses (csv)
    - created_at
    - updated_at

## Models relationships
- Booking:
    - belongsTo User
    - belongsTo Vehicle
- Vehicle:
    - hasMany Booking
- User:
    - hasMany Booking

## Project assumptions
- api first design:
    - define restful apis for all relevant operations (auth, crud, etc)
    - unit/integration tests are checking primarily the api
- using hashids for encoding/decoding (id obfuscating):
    - UUID-like public identifier generated via Hashids
- each model must have (crud-only-controller, policies, api-resources, factory)
- interfaces are located in: /app/Interfaces
- helpers (functions) are located in: /app/Helpers
- services (class) are located in: /app/Services
- enums are located in: /app/Misc/enums.php
- project specific configs in: /config/twr.php
- multi-language support is expected
- testing:
    - integration tests only (since a test function may use several components)
    - tests are testing only api calls (since the entire application is based around it)
    - structure:
        - auth tests: /tests/Feature/authTest.php
        - crud tests (for each model): /tests/Feature/{model_name}CRUDTest.php
        - others tests (for later)
- UI interface:
    - beige background
    - reddish accent color
    - white rabbit, red scarf, riding a red scooter

## Non-goals
- No real payment gateway integration (mocked/manual)
- No real-time availability syncing
- No mobile app

## Notes
- While real-world rental management systems often require extensive domain research, specifications and business consultation, this project takes a high-level approach and focuses primarily on architectural decisions and implementation quality from a web developer’s perspective.