# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a CakePHP 4.x application for "Hannah's Haus Cake" - a dog adoption management system. The application manages users, dogs, and adoption applications with authentication and a multi-step application process.

## Development Environment

### Docker Setup

The application uses Docker Compose with two services:
- `mysql8`: MySQL 8 database (exposed on port 9405)
- `php-fpm`: PHP 8.2 with Nginx (exposed on port 80)

**Start services:**
```bash
docker-compose up -d
```

**Stop services:**
```bash
docker-compose down
```

**Access the application:** http://localhost:80

### Local Database Configuration

Database connection is configured via environment variable:
```
DATABASE_URL=mysql://my_user:secret@hannahs_haus_db/my_app
```

For local development without Docker, copy `config/app_local.example.php` to `config/app_local.php` and configure the database settings.

## Common Commands

### Composer Scripts

```bash
# Run all tests
composer test
# or
vendor/bin/phpunit

# Run specific test file
vendor/bin/phpunit tests/TestCase/SomeTest.php

# Check coding standards
composer cs-check

# Fix coding standards automatically
composer cs-fix

# Run both tests and coding standards check
composer check
```

### CakePHP CLI

```bash
# Start built-in development server (without Docker)
bin/cake server -p 8765

# Run database migrations
bin/cake migrations migrate

# Create a migration
bin/cake migrations create MigrationName

# Rollback last migration
bin/cake migrations rollback

# Bake a controller
bin/cake bake controller ModelName

# Bake a model (Table + Entity)
bin/cake bake model ModelName

# Bake templates
bin/cake bake template ModelName
```

### Static Analysis

```bash
# Run PHPStan (Level 8)
phpstan analyze
```

## Architecture

### Authentication System

The application uses `cakephp/authentication` plugin configured in `src/Application.php`:
- **Identifier:** Email-based authentication (email + password)
- **Authenticators:** Session and Form authentication
- **Login URL:** `/users/login`
- **Unauthenticated redirects:** Automatically redirects to login page

### Core Domain Models

**Users:**
- Entity: `src/Model/Entity/User.php`
- Table: `src/Model/Table/UsersTable.php`
- Controller: `src/Controller/UsersController.php`
- Contains user profile data including housing information, contact preferences, and adoption history
- Fields include: email (unique), password, name, address, country, state, housing type, children status, dog ownership history

**Dogs:**
- Entity: `src/Model/Entity/Dog.php`
- Table: `src/Model/Table/DogsTable.php`
- Controller: `src/Controller/DogsController.php`
- Represents dogs available for adoption

**DogApplication:**
- Entity: `src/Model/Entity/DogApplication.php`
- Table: `src/Model/Table/DogApplicationTable.php`
- Controller: `src/Controller/DogApplicationController.php`
- Links users to dogs with adoption application details
- Contains approval workflow (approved field + approvedDate)
- Fields: userId, dogId, pickupMethodId, dateCreated, approved, approvedDate

### Supporting Lookup Tables

- **Countries:** `src/Model/Table/CountriesTable.php`
- **States:** `src/Model/Table/StatesTable.php`
- **HousingTypes:** `src/Model/Table/HousingTypesTable.php`

### Template Structure

Templates are located in `templates/` directory organized by controller:
- `templates/Users/` - User management views
- `templates/Dogs/` - Dog listing and management views
- `templates/DogApplication/` - Adoption application views
- `templates/Pages/` - Static pages (home, about, contact_us, adoption_policy, privacy_policy, gallery, thanks)
- `templates/layout/default.php` - Main layout template
- `templates/element/flash/` - Flash message elements

### Static Assets

Located in `webroot/`:
- `webroot/css/` - Styles (uses Milligram CSS framework)
- `webroot/js/` - JavaScript files organized by controller
- `webroot/img/` - Images

### Database Migrations

Located in `config/Migrations/`. Key migrations include:
- User table and profile fields (20240303101847, 20240303111242, 20240318080058)
- Lookup tables (contact methods, countries, states, housing types, pickup methods)
- Dogs table (20240303105316)
- Dog applications table (20240303111058)
- User contact preferences (20240303111216)
- Data seeding migrations for countries, states, and housing types

## Code Quality Standards

### PHP CodeSniffer

Configuration: `phpcs.xml`
- Standard: CakePHP coding standards
- Scanned directories: `src/`, `tests/`

### PHPStan

Configuration: `phpstan.neon`
- Level: 8 (maximum strictness)
- Analyzed path: `src/`
- Excluded: `src/Console/Installer.php`
- Settings: `checkMissingIterableValueType: false`, `treatPhpDocTypesAsCertain: false`

## CakePHP Conventions

When working with this codebase, follow CakePHP conventions:

1. **Controllers:** Named plurally (e.g., `UsersController`), located in `src/Controller/`
2. **Models:**
   - Tables: Plurally named (e.g., `UsersTable`), in `src/Model/Table/`
   - Entities: Singularly named (e.g., `User`), in `src/Model/Entity/`
3. **Templates:** Match controller actions, in `templates/{ControllerName}/{action}.php`
4. **Routing:** Uses DashedRoute class, fallback routes enabled in `config/routes.php`
5. **Database tables:** Use snake_case naming (e.g., `dog_application`, `housing_types`)

## Testing

- Test files: `tests/TestCase/`
- Test configuration: `phpunit.xml.dist`
- Test database: Use SQLite or configure `DATABASE_TEST_URL` environment variable
