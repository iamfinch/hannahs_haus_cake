# System Architecture Overview

**Last Updated:** 2025-11-02

This document provides high-level system architecture diagrams showing how all components of the Hannah's Haus Cake application interact.

## Overview

Hannah's Haus Cake is a containerized CakePHP 4.x application running three Docker services orchestrated through Docker Compose. The application provides a complete dog adoption management system with user authentication, multi-step adoption workflows, and email notifications. All services communicate through a dedicated Docker bridge network, ensuring isolated and secure inter-container communication.

## High-Level Architecture

```mermaid
graph LR
    subgraph "User Environment"
        Browser[User's Browser]
    end

    subgraph "Docker Network: application"
        subgraph "Web Container"
            PHP[php-fpm<br/>Nginx + PHP 8.2<br/>Port: 80]
            CAKE[CakePHP Application]
            PHP --> CAKE
        end

        subgraph "Database Container"
            DB[mysql8<br/>MySQL 8<br/>External: 9405<br/>Internal: 3306]
        end

        subgraph "Email Container"
            MAIL[mailhog<br/>SMTP: 1025<br/>Web UI: 8025]
        end
    end

    subgraph "Future External Services"
        AWS[AWS SES<br/>Production Email]
    end

    Browser -->|HTTP:80| PHP
    Browser -->|HTTP:8025| MAIL
    CAKE -->|mysql://| DB
    CAKE -->|SMTP:1025| MAIL
    CAKE -.->|Future| AWS

    style AWS stroke-dasharray: 5 5
    style PHP fill:#f9f,stroke:#333,stroke-width:2px
    style DB fill:#bbf,stroke:#333,stroke-width:2px
    style MAIL fill:#fbf,stroke:#333,stroke-width:2px
```

### Components Explained

**Web Server (php-fpm container):**
- **Purpose:** Serves the CakePHP application and handles all HTTP requests
- **Technology:** Nginx web server with PHP-FPM 8.2
- **Container Name:** myapp-webserver
- **Base Image:** webdevops/php-nginx:8.2
- **Accessible at:** http://localhost:80
- **Document Root:** /app/webroot (mapped from local ./webroot)

**Database (mysql8 container):**
- **Purpose:** Persistent data storage for all application entities
- **Technology:** MySQL 8 database server
- **Container Name:** hannahs_haus_db
- **Base Image:** mysql:8
- **External Access:** localhost:9405 (for database management tools)
- **Internal Access:** hannahs_haus_db:3306 (within Docker network)
- **Credentials:** Database: my_app, User: my_user, Password: secret

**Email Testing (mailhog container):**
- **Purpose:** Captures all outgoing emails for development testing
- **Technology:** MailHog email testing tool
- **Container Name:** hannahs_haus_mailhog
- **Base Image:** mailhog/mailhog:latest
- **SMTP Server:** localhost:1025 (for application email delivery)
- **Web UI:** http://localhost:8025 (for viewing captured emails)

## Docker Compose Configuration

```mermaid
graph TD
    subgraph "docker-compose.yml"
        DC[Docker Compose<br/>Orchestration]
    end

    subgraph "Services"
        MySQL[mysql8<br/>mysql:8]
        PHP[php-fpm<br/>webdevops/php-nginx:8.2]
        MH[mailhog<br/>mailhog/mailhog:latest]
    end

    subgraph "Port Mappings"
        P1[9405:3306<br/>Database]
        P2[80:80<br/>Web App]
        P3[1025:1025<br/>SMTP]
        P4[8025:8025<br/>Mail UI]
    end

    subgraph "Volumes"
        V1[./:/application<br/>MySQL Volume]
        V2[./:/app<br/>PHP Volume]
    end

    subgraph "Network"
        NET[application<br/>Bridge Network]
    end

    DC --> MySQL
    DC --> PHP
    DC --> MH

    MySQL --> P1
    MySQL --> V1
    MySQL --> NET

    PHP --> P2
    PHP --> V2
    PHP --> NET
    PHP -.->|depends_on| MySQL

    MH --> P3
    MH --> P4
    MH --> NET

    style DC fill:#ffd,stroke:#333,stroke-width:3px
    style NET fill:#dfd,stroke:#333,stroke-width:2px
```

### Service Dependencies

The Docker Compose configuration establishes a clear dependency chain:

1. **mysql8** starts first as the foundational data layer
2. **php-fpm** starts after mysql8 (explicit `depends_on` relationship)
3. **mailhog** starts independently (no dependencies)

This ensures the database is available before the application attempts to connect, preventing startup failures due to missing database connections.

### Port Mappings

| Service | Internal Port | External Port | Purpose |
|---------|---------------|---------------|----------|
| php-fpm | 80 | 80 | Web application access |
| mysql8 | 3306 | 9405 | Database management access |
| mailhog | 1025 | 1025 | SMTP email delivery |
| mailhog | 8025 | 8025 | Email testing web interface |

### Networks

All services communicate over the "application" bridge network, which provides:
- **Service Discovery:** Containers can reach each other by service name (e.g., hannahs_haus_db)
- **Isolation:** Traffic is isolated from other Docker networks and the host
- **Security:** Only explicitly exposed ports are accessible from outside the network
- **DNS Resolution:** Automatic hostname resolution between containers

### Environment Variables

Key environment configurations:

**php-fpm service:**
```
WEB_DOCUMENT_ROOT=/app/webroot
DATABASE_URL=mysql://my_user:secret@hannahs_haus_db/my_app
```

**mysql8 service:**
```
MYSQL_ROOT_PASSWORD=NarrativeNonsense21!
MYSQL_DATABASE=my_app
MYSQL_USER=my_user
MYSQL_PASSWORD=secret
```

## Development vs. Production

**Current Configuration (Development):**
- Uses MailHog for email testing (all emails captured locally)
- Database runs in local Docker container with exposed port
- Debug mode enabled in CakePHP
- Port 80 for easy local access
- Volumes mount local filesystem for live code reloading
- No SSL/TLS certificates required

**Future Production Configuration:**
- Will use AWS SES for email delivery
- May use Amazon RDS or managed database service
- Debug mode disabled for security
- HTTPS with proper SSL certificates
- Static assets served from CDN
- Environment-specific configuration via environment variables
- No direct database port exposure
- Read-only filesystem mounts for application code

## Common Docker Commands

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View running services
docker ps

# View service logs
docker-compose logs -f

# Access application logs
docker logs myapp-webserver

# Access database logs
docker logs hannahs_haus_db

# Access database CLI
docker exec -it hannahs_haus_db mysql -u my_user -p
# Password: secret

# Access PHP container shell
docker exec -it myapp-webserver bash

# Access MailHog web UI
open http://localhost:8025

# Run CakePHP commands
docker exec myapp-webserver bin/cake [command]

# Run database migrations
docker exec myapp-webserver bin/cake migrations migrate

# Run tests
docker exec myapp-webserver vendor/bin/phpunit

# Rebuild containers after Dockerfile changes
docker-compose build --no-cache

# Remove all containers and networks
docker-compose down -v
```

## Troubleshooting

### Port Conflicts

If ports 80, 9405, 1025, or 8025 are already in use:

1. **Check what's using the port:**
   ```bash
   lsof -i :80  # Check port 80
   lsof -i :9405  # Check port 9405
   ```

2. **Stop conflicting services or change ports in docker-compose.yml:**
   ```yaml
   ports:
     - "8080:80"  # Change external port to 8080
   ```

### Container Won't Start

1. **Check container logs:**
   ```bash
   docker logs hannahs_haus_db
   docker logs myapp-webserver
   ```

2. **Verify dependencies are running:**
   ```bash
   docker ps -a  # Show all containers, including stopped
   ```

3. **Ensure Docker daemon is running:**
   ```bash
   docker version
   ```

### Database Connection Issues

1. **Verify DATABASE_URL environment variable:**
   ```bash
   docker exec myapp-webserver printenv DATABASE_URL
   ```

2. **Test database connectivity:**
   ```bash
   docker exec myapp-webserver ping hannahs_haus_db
   ```

3. **Check database is accepting connections:**
   ```bash
   docker exec -it hannahs_haus_db mysql -u my_user -p -e "SELECT 1"
   ```

4. **Verify network connectivity:**
   ```bash
   docker network inspect hannahs_haus_cake_application
   ```

### Email Not Being Captured

1. **Verify MailHog is running:**
   ```bash
   docker ps | grep mailhog
   ```

2. **Check MailHog web interface:**
   ```bash
   open http://localhost:8025
   ```

3. **Test SMTP connectivity:**
   ```bash
   telnet localhost 1025
   ```

4. **Verify CakePHP email configuration** in `config/app.php` or `config/app_local.php`

## Performance Considerations

### Development Environment

- **File System Mounts:** Local volume mounts may cause slower performance on macOS/Windows
- **Memory Allocation:** Ensure Docker has sufficient memory (minimum 2GB recommended)
- **CPU Allocation:** Assign at least 2 CPU cores to Docker Desktop

### Optimization Tips

1. **Use Docker volumes for better I/O performance:**
   ```yaml
   volumes:
     - app_data:/app  # Named volume instead of bind mount
   ```

2. **Enable BuildKit for faster builds:**
   ```bash
   export DOCKER_BUILDKIT=1
   docker-compose build
   ```

3. **Prune unused resources regularly:**
   ```bash
   docker system prune -a
   ```

## Security Notes

### Development Environment

- **Passwords in docker-compose.yml:** Acceptable for development, but use secrets management in production
- **Port Exposure:** All ports are exposed to localhost only
- **Database Root Access:** Root password is set but should be changed for production

### Production Recommendations

1. Use Docker secrets or environment files for sensitive data
2. Implement proper firewall rules
3. Use read-only containers where possible
4. Regular security updates for base images
5. Implement proper logging and monitoring
6. Use non-root users in containers

## Related Documentation

- [Database ERD - Core Domain](database-erd-core.md) - Core business entity relationships
- [Database ERD - Lookup Tables](database-erd-lookups.md) - Supporting lookup table structures
- [Authentication & Authorization Flow](auth-flow.md) - Security implementation details
- [Email Notification Flow](../workflows/email-notifications.md) - Email system documentation
- [Development Setup](../../README.md) - Quick start guide
- [CLAUDE.md](../../CLAUDE.md) - Project conventions and guidelines

## Monitoring and Logging

### Container Logs

All containers output logs that can be accessed via Docker:

```bash
# Follow all logs
docker-compose logs -f

# Specific service logs
docker-compose logs -f php-fpm
docker-compose logs -f mysql8
docker-compose logs -f mailhog

# Last 100 lines
docker-compose logs --tail=100 php-fpm
```

### Application Logs

CakePHP application logs are stored in:
- **Location:** `/app/logs/` within the container
- **Local Path:** `./logs/` in your project directory
- **Files:** `error.log`, `debug.log`, `cli-error.log`, `cli-debug.log`

### Database Logs

MySQL logs can be accessed through:
```bash
docker exec -it hannahs_haus_db bash
cat /var/log/mysql/error.log
```

## Backup and Restore

### Database Backup

```bash
# Create backup
docker exec hannahs_haus_db mysqldump -u my_user -psecret my_app > backup.sql

# Restore backup
docker exec -i hannahs_haus_db mysql -u my_user -psecret my_app < backup.sql
```

### Application State

Since the application code is mounted from the local filesystem, no separate backup is needed for code. Ensure your Git repository is up to date.

## Next Steps

1. Review the [Database ERD documentation](database-erd-core.md) to understand the data model
2. Check the [Authentication Flow](auth-flow.md) for security implementation
3. Follow the [Development Setup](../../README.md) for getting started
4. Configure your IDE for Docker development
5. Set up database management tools to connect to port 9405