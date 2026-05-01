# Booking System (Simple Project)

A simple booking system.

## Usage

Create an empty database, then update database credentials in `.env` file.

Run the following commands:

```bash
composer install
```

```bash
php artisan migrate
```
```bash
php artisan serv
```

##URL
base url is : http://localhost:8000

##RESTFUL APIS URL 
http://localhost:8000/api

## API Documentation

https://documenter.getpostman.com/view/20054298/2sBXqKnzeh

## Key Design Decisions

- Each User can create a Service Provider profile, but only one provider per user is allowed to keep the system simple and avoid unnecessary complexity.
- `provider_id` is used instead of `user_id` when linking services and bookings, since these operations are related to the user’s role as a service provider, not just a regular user.
- The Provider owns the services, and Users can book those services through the booking system.
- The relationships are designed clearly:
  - Provider → Services
  - User → Bookings
  - Booking connects User and Provider
- The system is designed as a simple MVP while keeping scalability in mind for future enhancements.
```
