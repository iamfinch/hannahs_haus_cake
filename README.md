# Hannah's Haus Cake - Dog Adoption Management System

A production-ready CakePHP 4.x application for managing dog adoption applications with comprehensive authentication, authorization, and email notification workflows.

**Project Status:** ✅ Active Development | 🧪 Test Coverage: 21 test files | 📊 PHPStan Level 8

---

## 📖 Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Quick Start](#quick-start)
- [Docker Services](#docker-services)
- [Development Workflow](#development-workflow)
- [Testing](#testing)
- [Architecture](#architecture)
- [Email Notifications](#email-notifications)
- [Documentation](#documentation)
- [Troubleshooting](#troubleshooting)

---

## Overview

Hannah's Haus Cake is a comprehensive dog adoption management system that streamlines the adoption process from application submission through approval and adoption completion. Built with CakePHP 4.x, the application features sophisticated authorization policies, automated email notifications, and a complete admin workflow for managing adoption applications.

### Technology Stack

- **Framework:** CakePHP 4.5+
- **PHP:** 8.2+
- **Database:** MySQL 8
- **Containerization:** Docker Compose (3 services)
- **CSS Framework:** Milligram (minimalist)
- **JavaScript:** jQuery (client-side validation)
- **Email Testing:** MailHog (development)
- **Code Quality:** PHPStan Level 8, CakePHP CodeSniffer
- **Testing:** PHPUnit 9.6 with comprehensive test coverage

---

## Key Features

### 🔐 Authentication & Authorization
- Email-based authentication with secure password hashing (bcrypt)
- Policy-based authorization using CakePHP authorization plugin
- Three-tier role system: Guest, User, Admin
- Resource ownership patterns (users manage own profiles)
- Admin privilege escalation for sensitive operations

### 🐕 Dog Management
- Browse available dogs for adoption
- Detailed dog profiles with photos and descriptions
- Adoption status tracking (available, adopted, retired)
- Admin-only dog management (add, edit, delete)

### 📝 Adoption Application Workflow
- User-friendly multi-step application process
- Tri-state approval system: Pending → Approved/Rejected
- Admin dashboard for application review and management
- Pickup method selection and coordination
- Automatic rejection of competing applications on approval
- Complete application history tracking

### 📧 Email Notifications
- **4 email types** with professional HTML and plain text templates
- **Application Submitted:** Confirmation to applicant + admin notification
- **Application Approved:** Celebratory email with next steps
- **Application Rejected:** Empathetic messaging with encouragement
- **Admin Alerts:** New application notifications with applicant details
- MailHog integration for development testing
- AWS SES ready for production deployment

### 🧪 Comprehensive Testing
- **21 test files** covering controllers, models, and policies
- Integration tests with authentication and CSRF mocking
- Policy authorization tests with mock identities
- Fixture-based test data with documented dependencies
- 100% passing test suite

---

## Quick Start

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/)
- Git

### Installation (5 minutes)

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd hannahs_haus_cake
   ```

2. **Start Docker services:**
   ```bash
   docker-compose up -d
   ```

3. **Run database migrations:**
   ```bash
   docker exec myapp-webserver bin/cake migrations migrate
   ```

4. **Access the application:**
   - **Web Application:** http://localhost:80
   - **MailHog UI:** http://localhost:8025

5. **Create an admin user:**
   See [Admin User Setup Guide](docs/development/admin-setup.md)

That's it! The application is now running with all services available.

---

## Docker Services

The application runs three Docker containers:

| Service | Container Name | Ports | Purpose |
|---------|---------------|-------|---------|
| **php-fpm** | myapp-webserver | 80 → 80 | Web application (Nginx + PHP 8.2) |
| **mysql8** | hannahs_haus_db | 9405 → 3306 | MySQL 8 database |
| **mailhog** | hannahs_haus_mailhog | 1025 (SMTP), 8025 (Web UI) | Email testing |

### Common Docker Commands

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View running containers
docker ps

# View logs
docker logs myapp-webserver
docker logs hannahs_haus_db
docker logs hannahs_haus_mailhog

# Access PHP container shell
docker exec -it myapp-webserver bash

# Access MySQL database
docker exec -it hannahs_haus_db mysql -u my_user -p

# Restart a specific service
docker-compose restart php-fpm
```

**📚 Detailed Architecture:** See [System Architecture Overview](docs/architecture/system-overview.md)

---

## Development Workflow

### Running Commands in Docker

All CakePHP commands should be run inside the PHP container:

```bash
# Template for running commands
docker exec myapp-webserver [command]
```

### Common Development Tasks

#### Database Migrations

```bash
# Run all pending migrations
docker exec myapp-webserver bin/cake migrations migrate

# Create a new migration
docker exec myapp-webserver bin/cake migrations create MigrationName

# Rollback last migration
docker exec myapp-webserver bin/cake migrations rollback

# Check migration status
docker exec myapp-webserver bin/cake migrations status
```

#### Code Quality

```bash
# Run all tests
docker exec myapp-webserver composer test
# or
docker exec myapp-webserver vendor/bin/phpunit

# Run specific test file
docker exec myapp-webserver vendor/bin/phpunit tests/TestCase/Controller/UsersControllerTest.php

# Check coding standards
docker exec myapp-webserver composer cs-check

# Fix coding standards automatically
docker exec myapp-webserver composer cs-fix

# Run PHPStan (Level 8)
docker exec myapp-webserver vendor/bin/phpstan analyze

# Run all checks (tests + coding standards)
docker exec myapp-webserver composer check
```

#### Baking New Components

```bash
# Bake a controller
docker exec myapp-webserver bin/cake bake controller ModelName

# Bake a model (Table + Entity)
docker exec myapp-webserver bin/cake bake model ModelName

# Bake templates
docker exec myapp-webserver bin/cake bake template ModelName
```

---

## Testing

### Running the Test Suite

```bash
# Run all tests
docker exec myapp-webserver vendor/bin/phpunit

# Run tests with coverage report
docker exec myapp-webserver vendor/bin/phpunit --coverage-html webroot/coverage

# Run specific test class
docker exec myapp-webserver vendor/bin/phpunit tests/TestCase/Controller/DogApplicationControllerTest.php

# Run specific test method
docker exec myapp-webserver vendor/bin/phpunit --filter testApplyRequiresAuthentication
```

### Test Coverage

- **21 test files** with comprehensive scenarios
- **Controller tests:** Authentication, authorization, validation, edge cases
- **Model tests:** Business logic, associations, custom finders
- **Policy tests:** Authorization rules with mock identities (Admin, User, Guest)
- **Mailer tests:** Email configuration, recipients, templates, lazy loading

**📚 Testing Guide:** See [Testing Patterns Documentation](docs/development/testing-patterns.md)

---

## Architecture

### Domain Models

**Core Entities:**
- **Users** - Authentication, profiles, housing information, adoption history
- **Dogs** - Available dogs for adoption with status tracking
- **DogApplication** - Links users to dogs with approval workflow

**Supporting Tables:**
- Countries, States (geographic hierarchy)
- HousingTypes (user profile enrichment)
- PickupMethods (adoption logistics)
- ContactMethods + UserContactPreferences (many-to-many)

### Authentication System

- **Plugin:** cakephp/authentication
- **Identifier:** Email-based (email + password)
- **Authenticators:** Session and Form
- **Login URL:** `/users/login`
- **Public Actions:** `index` and `view` by default

### Authorization System

- **Plugin:** cakephp/authorization
- **Pattern:** Policy-based authorization
- **Policies:** UserPolicy, DogPolicy, DogApplicationPolicy
- **Admin Role:** `isAdmin` flag provides elevated permissions

**📚 Detailed Documentation:**
- [System Architecture Overview](docs/architecture/system-overview.md)
- [Database ERD - Core Domain](docs/architecture/database-erd-core.md)
- [Database ERD - User Profile & Lookups](docs/architecture/database-erd-lookups.md)
- [Authentication & Authorization Flow](docs/architecture/auth-flow.md)
- [Adoption Application Workflow](docs/workflows/adoption-process.md)

---

## Email Notifications

The application sends automated email notifications at key points in the adoption workflow.

### Email Types

1. **Application Submitted** - Confirmation to applicant + admin notification
2. **Application Approved** - Celebratory email with next steps
3. **Application Rejected** - Empathetic messaging with encouragement to apply for other dogs
4. **Admin New Application** - Alert to admin with applicant details for review

### Development Email Testing with MailHog

**MailHog** captures all outgoing emails in development without sending real emails.

1. **Access MailHog Web UI:** http://localhost:8025
2. **Submit an adoption application** via the web interface
3. **View captured emails** in MailHog (HTML + text versions)
4. **Verify email content** renders correctly

All emails have professional HTML templates with inline CSS for maximum email client compatibility, plus plain text versions for accessibility.

### Production Email Configuration

For production deployment, the application is configured to use **AWS SES** (Amazon Simple Email Service). Configuration is ready in `config/app.php` but commented out. See [Email Notifications Documentation](docs/workflows/email-notifications.md) for setup instructions.

**📧 Email System Details:**
- [Email Notification Flow Documentation](docs/workflows/email-notifications.md)
- ApplicationMailer class: `src/Mailer/ApplicationMailer.php`
- Email templates: `templates/email/html/` and `templates/email/text/`

---

## Documentation

### Visual Documentation (Mermaid Diagrams)

Comprehensive visual documentation with **16 Mermaid diagrams** covering:

- **Database Schema** - Complete ERDs with all tables and relationships
- **Authentication & Authorization** - Login flows, policy decisions, role hierarchy
- **Adoption Workflow** - State machines, user journeys, admin approval process
- **Email System** - Notification flows, template architecture, integration points
- **Testing Architecture** - Test patterns, fixture dependencies, test flows
- **System Architecture** - Docker services, component interactions, deployment

**📚 Documentation Index:** [docs/README.md](docs/README.md)

### Key Documentation Files

| Document | Description |
|----------|-------------|
| [CLAUDE.md](CLAUDE.md) | Complete developer guide, conventions, setup instructions |
| [docs/README.md](docs/README.md) | Visual documentation index with all Mermaid diagrams |
| [docs/architecture/](docs/architecture/) | Database ERDs, auth flow, system architecture |
| [docs/workflows/](docs/workflows/) | Adoption process, email notifications |
| [docs/development/](docs/development/) | Testing patterns, admin setup guide |

### Non-Technical Overview

For product managers, stakeholders, or non-technical team members:
- [Project Overview for Non-Technical Readers](docs/overview/project-overview.md)

---

## Troubleshooting

### Port Already in Use

If you see "port is already allocated" errors:

```bash
# Check what's using the ports
lsof -i :80    # Web application
lsof -i :9405  # MySQL
lsof -i :8025  # MailHog UI
lsof -i :1025  # MailHog SMTP

# Stop conflicting services or change ports in docker-compose.yml
```

### Database Connection Failed

```bash
# Verify database container is running
docker ps | grep hannahs_haus_db

# Check database logs
docker logs hannahs_haus_db

# Verify DATABASE_URL environment variable
docker exec myapp-webserver env | grep DATABASE_URL

# Test database connection
docker exec -it hannahs_haus_db mysql -u my_user -p
```

### Emails Not Appearing in MailHog

```bash
# Verify MailHog container is running
docker ps | grep mailhog

# Check MailHog logs
docker logs hannahs_haus_mailhog

# Verify email configuration
docker exec myapp-webserver cat config/app.php | grep -A 10 "EmailTransport"

# Access MailHog UI directly
open http://localhost:8025
```

### Test Failures

```bash
# Clear cache
docker exec myapp-webserver bin/cake cache clear_all

# Ensure test database is set up
docker exec myapp-webserver vendor/bin/phpunit --migrate-configuration

# Run single test with verbose output
docker exec myapp-webserver vendor/bin/phpunit --verbose tests/TestCase/Controller/UsersControllerTest.php
```

### Docker Issues

```bash
# Rebuild containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Remove all containers and volumes (⚠️ destroys data)
docker-compose down -v

# Check disk space
docker system df

# Clean up unused resources
docker system prune
```

---

## CakePHP Conventions

This project follows CakePHP naming conventions:

- **Controllers:** Plurally named (e.g., `UsersController`)
- **Models:** Tables plurally named (e.g., `UsersTable`), Entities singularly named (e.g., `User`)
- **Database Tables:** snake_case (e.g., `dog_application`, `housing_types`)
- **Templates:** Match controller actions in `templates/{ControllerName}/{action}.php`
- **Routing:** DashedRoute class with fallback routes enabled

**📚 CakePHP Resources:**
- [CakePHP 4.x Documentation](https://book.cakephp.org/4/en/index.html)
- [CakePHP Authentication Plugin](https://book.cakephp.org/authentication/2/en/index.html)
- [CakePHP Authorization Plugin](https://book.cakephp.org/authorization/2/en/index.html)

---

## Contributing

### Development Guidelines

1. **Write tests** for all new features
2. **Follow PSR-2** coding standards (with CakePHP modifications)
3. **Pass PHPStan Level 8** analysis
4. **Document complex logic** with inline comments
5. **Update Mermaid diagrams** when modifying architecture or workflows
6. **Run all checks** before committing: `composer check`

### Git Workflow

1. Create feature branch from `main`
2. Make changes with descriptive commits
3. Run test suite: `composer test`
4. Run code quality checks: `composer cs-check && phpstan analyze`
5. Submit pull request with detailed description
6. Ensure all tests pass in CI

---

## License

This project is proprietary software for Hannah's Haus Cake dog adoption organization.

---

## Support

For questions, issues, or contributions:
- Check the [documentation](docs/README.md)
- Review [CLAUDE.md](CLAUDE.md) for development guidelines
- Consult [troubleshooting section](#troubleshooting) above

---

**Happy coding! 🐕✨**
