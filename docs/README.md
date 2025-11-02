# Hannah's Haus Cake Documentation

**Last Updated:** 2025-11-02

Welcome to the visual documentation for the Hannah's Haus Cake dog adoption management system. This documentation provides comprehensive diagrams and explanations of the system architecture, database schema, authentication, authorization, and business workflows.

## 📚 Documentation Overview

This documentation suite uses [Mermaid JS](https://mermaid.js.org/) for all diagrams, which render automatically in GitHub, GitLab, VS Code (with Mermaid extension), and most modern markdown viewers.

### What You'll Find Here

- **Database Schema Diagrams** - Entity Relationship Diagrams showing table structures and relationships
- **Architecture Diagrams** - Authentication and authorization flow visualizations
- **Workflow Diagrams** - Business process flows including the adoption application workflow
- **Development Guides** - Testing patterns and development workflow documentation (future)

---

## 🗂️ Documentation Structure

```
docs/
├── architecture/          # System architecture and technical design
│   ├── database-erd-core.md
│   ├── database-erd-lookups.md
│   ├── auth-flow.md
│   └── system-overview.md
├── workflows/            # Business process workflows
│   ├── adoption-process.md
│   └── email-notifications.md
├── development/          # Development practices and patterns
│   ├── testing-patterns.md
│   └── admin-setup.md
├── overview/             # Non-technical project overviews
│   └── project-overview.md
└── README.md            # This file
```

---

## 🏗️ Architecture Documentation

### Database Schema

#### [Core Domain ERD](architecture/database-erd-core.md)
**Entity Relationship Diagram - Core Business Entities**

Visualizes the primary domain model for the dog adoption system:
- **Tables**: `users`, `dogs`, `dog_application`, `pickup_methods`
- **Key Relationships**:
  - Users submit adoption applications for dogs
  - Dogs have optional current owners (nullable userId)
  - Applications track approval workflow
- **Patterns Highlighted**:
  - Tri-state approval system (approved: "-1", "0", "1")
  - Soft delete pattern (retired/adopted flags)
  - Admin authorization via isAdmin flag

**When to use this diagram:** Understanding the core business entities and how adoption applications link users to dogs.

---

#### [User Profile & Lookup Tables ERD](architecture/database-erd-lookups.md)
**Entity Relationship Diagram - User Profile & Reference Data**

Documents the user profile structure and all lookup/reference tables:
- **Tables**: `users`, `countries`, `states`, `housing_types`, `contact_methods`, `userContactPreferences`
- **Key Relationships**:
  - Geographic hierarchy (countries → states) with CASCADE delete
  - User profile foreign keys to lookup tables
  - Many-to-many contact preferences via junction table
- **Patterns Highlighted**:
  - Comprehensive user profile fields (housing, family, pet history)
  - Lookup table pattern for dropdowns
  - Many-to-many junction table for contact preferences

**When to use this diagram:** Understanding user profile data requirements, geographic data relationships, or lookup table structures.

---

### Authentication & Authorization

#### [Authentication & Authorization Flow](architecture/auth-flow.md)
**Complete Security Model Documentation**

Comprehensive documentation of the authentication and authorization system:

**Diagrams Included:**
1. **Login Authentication Sequence** - Step-by-step login process with all components
2. **Authorization Decision Flowchart** - How policy-based authorization determines access
3. **Role Hierarchy & Permissions** - Three-tier role system (Guest, User, Admin)

**Key Topics:**
- Email-based authentication with session management
- Policy-based authorization using CakePHP authorization plugin
- Admin privilege escalation (isAdmin flag)
- Resource ownership patterns (users can edit own profiles)
- Security considerations (bcrypt, CSRF, session security)

**When to use this diagram:** Understanding how login works, how permissions are checked, or implementing new authorization policies.

---

### System Architecture

#### [System Architecture Overview](architecture/system-overview.md)
**Complete System Topology and Docker Configuration**

High-level system architecture documentation showing all components and their relationships:

**Diagrams Included:**
1. **High-Level Architecture** - Complete system topology with all Docker services and external dependencies
2. **Docker Compose Configuration** - Service relationships, port mappings, networks, and volumes

**Key Topics:**
- Docker services: mysql8, php-fpm, mailhog
- Port mappings and external access points
- Network configuration (bridge network)
- Volume mounts and data persistence
- Development vs. production configuration
- Common Docker commands for daily operations
- Troubleshooting Docker issues
- Performance considerations and optimization
- Security best practices
- Backup and restore procedures

**Service Details:**
- **php-fpm**: Nginx + PHP 8.2 web server (port 80)
- **mysql8**: MySQL 8 database (port 9405 external, 3306 internal)
- **mailhog**: Email testing service (SMTP 1025, Web UI 8025)

**When to use this diagram:** Understanding Docker setup, troubleshooting container issues, setting up new development environments, or planning deployment architecture.

---

## 💼 Workflow Documentation

### Adoption Process

#### [Dog Adoption Application Workflow](workflows/adoption-process.md)
**Complete Adoption Process from Application to Completion**

Detailed documentation of the multi-step adoption workflow:

**Diagrams Included:**
1. **Application State Machine** - All application states and transitions
2. **User Adoption Journey** - Sequence diagram showing complete user experience
3. **Admin Approval Decision Process** - Flowchart for admin review and approval

**Key Topics:**
- Tri-state approval system (Pending → Approved/Rejected → Completed)
- User application submission process
- Admin review and approval workflow
- Dog availability management (retired/adopted flags)
- Pickup method selection and coordination
- Edge case handling (cancellations, concurrent applications)

**When to use this diagram:** Understanding the adoption business process, implementing approval features, or troubleshooting application state issues.

---

### Email Notification System

#### [Email Notification System](workflows/email-notifications.md)
**Complete Email Notification Documentation with Integration Architecture**

Comprehensive documentation of the automated email notification system:

**Diagrams Included:**
1. **Email Notification Flow** - Sequence diagram showing all 4 email scenarios (submission, approval, rejection, admin alerts)
2. **ApplicationMailer Integration Architecture** - Flowchart showing controller-to-mailer-to-transport flow

**Key Topics:**
- ApplicationMailer class with 4 email methods
- Email templates (8 total: HTML + text versions)
- MailHog development testing setup
- AWS SES production configuration
- Controller integration patterns (apply, approve, reject actions)
- Lazy loading pattern for associations
- Error handling and logging
- Template structure and design
- Testing email notifications
- Troubleshooting common issues

**Email Scenarios Documented:**
- Application submission confirmation (user + admin notification)
- Application approval celebration email
- Application rejection empathetic messaging
- Admin new application alerts with applicant details

**When to use this diagram:** Implementing new email notifications, troubleshooting email delivery issues, understanding template structure, or configuring production email transport.

---

## 🧪 Development Documentation

### Testing Architecture & Patterns

#### [Testing Patterns](development/testing-patterns.md)
**Complete Test Suite Documentation with Patterns & Best Practices**

Comprehensive documentation of the testing architecture and methodologies:

**Diagrams Included:**
1. **Testing Architecture Overview** - Hierarchical structure showing test types (integration, unit, policy tests)
2. **Fixture Dependency Graph** - Visual representation of fixture loading order and foreign key relationships
3. **Controller Integration Test Flow** - Sequence diagram of full HTTP request lifecycle in tests
4. **Policy Test Pattern Flowchart** - Authorization testing pattern with mock identities

**Key Topics:**
- Controller integration testing with IntegrationTestTrait
- Policy authorization testing with mock identities (Admin, User, Guest)
- Model/Table unit testing for business logic
- Fixture management and dependency ordering
- Authentication and CSRF mocking patterns
- Database state assertions
- Best practices and common patterns
- Running tests locally and in Docker

**Test Coverage:**
- 22 test files (13 controller, 6 model, 3 policy)
- 7 fixtures with documented purposes
- Comprehensive test scenarios (authentication, authorization, validation, edge cases)

**When to use this diagram:** Writing new tests, understanding test patterns, debugging test failures, or learning the fixture dependency structure.

---

### Admin User Management

#### [Admin User Setup Guide](development/admin-setup.md)
**Complete Guide to Creating and Managing Administrator Accounts**

Comprehensive documentation for creating and managing admin users with elevated privileges:

**Key Topics:**
- What is an admin user and what privileges they have
- Current implementation (manual SQL script process)
- Step-by-step instructions for creating admin users (3 methods documented)
- Database verification procedures
- Application verification and testing
- Managing admin users (listing, revoking privileges)
- Troubleshooting common issues
- Security considerations and best practices
- Future enhancements (automated seeding system)

**Admin Privileges:**
- Approve/reject adoption applications
- Access admin dashboard with application statistics
- Manage dog listings (add, edit, delete)
- View all user information for application review
- Receive automatic notifications for new applications

**Related Resources:**
- SQL script: `private/sql/create-admin-user.sql`
- SQL directory guide: `private/sql/README.md`

**When to use this guide:** Setting up new admin users, troubleshooting admin access issues, understanding admin permissions, or planning admin management features.

---

## 📋 Non-Technical Documentation

### Project Overview

#### [Project Overview for Non-Technical Readers](overview/project-overview.md)
**Business-Focused System Overview for Stakeholders**

Simplified documentation written for non-technical audiences including product managers, business analysts, and stakeholders:

**Key Topics:**
- What is Hannah's Haus Cake (plain language explanation)
- Who uses the system (adopters and administrators)
- Key features explained without jargon
- User journeys and workflows in simple terms
- Benefits for staff and adopters
- Current status and future enhancements
- Common questions and answers
- Glossary of technical terms

**Target Audience:**
- Product managers and business analysts
- Non-technical stakeholders
- New team members unfamiliar with technical details
- Management reviewing project status

**When to use this guide:** Onboarding non-technical team members, explaining the system to stakeholders, providing business context, or creating presentations about the project.

---

## 🎯 Quick Navigation

### For New Developers
Start here to understand the system:
1. [System Architecture Overview](architecture/system-overview.md) - Docker setup and system topology
2. [Core Domain ERD](architecture/database-erd-core.md) - Understand the main entities
3. [Authentication & Authorization Flow](architecture/auth-flow.md) - Learn the security model
4. [Adoption Workflow](workflows/adoption-process.md) - Understand the business process
5. [Email Notifications](workflows/email-notifications.md) - Learn the email system
6. [Testing Patterns](development/testing-patterns.md) - Learn how to write and run tests
7. [Admin Setup Guide](development/admin-setup.md) - How to create admin users

### For Backend Developers
- [System Architecture Overview](architecture/system-overview.md) - Docker services and deployment
- [Core Domain ERD](architecture/database-erd-core.md) - Database relationships
- [User Profile ERD](architecture/database-erd-lookups.md) - Complete schema details
- [Authorization Flow](architecture/auth-flow.md) - Policy implementation
- [Email Notifications](workflows/email-notifications.md) - Email system architecture and templates
- [Testing Patterns](development/testing-patterns.md) - Test suite architecture and patterns
- [Admin Setup Guide](development/admin-setup.md) - Admin user management

### For Product Managers / Business Analysts
- [Project Overview](overview/project-overview.md) - Non-technical system overview
- [Adoption Workflow](workflows/adoption-process.md) - Business process overview
- [Email Notifications](workflows/email-notifications.md) - User communication touchpoints
- [Authorization Flow - Role Hierarchy](architecture/auth-flow.md#role-based-access-control) - User permissions

### For DevOps / Database Administrators
- [System Architecture Overview](architecture/system-overview.md) - Docker configuration and deployment
- [Core Domain ERD](architecture/database-erd-core.md) - Primary tables and indexes
- [User Profile ERD](architecture/database-erd-lookups.md) - Complete schema with foreign keys
- [Admin Setup Guide](development/admin-setup.md) - Admin user creation and management

---

## 🔍 Diagram Types Used

This documentation uses several Mermaid diagram types:

### Entity Relationship Diagrams (ERD)
- **Purpose**: Visualize database schema, tables, columns, and relationships
- **Used In**: database-erd-core.md, database-erd-lookups.md
- **Notation**:
  - `||--o{` = One-to-many
  - `||--||` = One-to-one
  - `}o--o{` = Many-to-many

### Sequence Diagrams
- **Purpose**: Show interactions between components over time
- **Used In**: auth-flow.md (login sequence), adoption-process.md (user journey)
- **Components**: Users, Controllers, Services, Database

### State Diagrams
- **Purpose**: Visualize state transitions and lifecycles
- **Used In**: adoption-process.md (application states)
- **Notation**: States, transitions, terminal states

### Flowcharts
- **Purpose**: Decision trees and process flows
- **Used In**: auth-flow.md (authorization decisions), adoption-process.md (admin approval)
- **Notation**: Rectangles (processes), Diamonds (decisions), Arrows (flow)

---

## 📖 Related Documentation

### Primary Documentation
- **[CLAUDE.md](../CLAUDE.md)** - Complete project overview, setup instructions, conventions
- **README.md** (root) - Project README with quick start guide

### Source Code References
- **Controllers**: `src/Controller/` - Application logic and request handling
- **Models**: `src/Model/Table/`, `src/Model/Entity/` - Data models
- **Policies**: `src/Policy/` - Authorization policies
- **Migrations**: `config/Migrations/` - Database schema definitions
- **Templates**: `templates/` - View layer

### External Resources
- [CakePHP 4.x Documentation](https://book.cakephp.org/4/en/index.html)
- [CakePHP Authentication Plugin](https://book.cakephp.org/authentication/2/en/index.html)
- [CakePHP Authorization Plugin](https://book.cakephp.org/authorization/2/en/index.html)
- [Mermaid JS Documentation](https://mermaid.js.org/)

---

## 🛠️ Viewing the Diagrams

### In GitHub/GitLab
Diagrams render automatically when viewing markdown files in the web interface.

### In VS Code
Install the **Mermaid Preview** extension:
- [Markdown Preview Mermaid Support](https://marketplace.visualstudio.com/items?itemName=bierner.markdown-mermaid)
- Open any `.md` file and use the preview pane (Cmd/Ctrl + Shift + V)

### In Other Editors
- **IntelliJ/PhpStorm**: Built-in Mermaid support in markdown preview
- **Obsidian**: Native Mermaid rendering
- **MkDocs/Docusaurus**: Built-in or plugin-based support

### Online Viewer
Test diagrams at [Mermaid Live Editor](https://mermaid.live) - Copy/paste Mermaid code blocks to view and edit.

---

## 🔄 Keeping Documentation Updated

### When to Update Diagrams

**Update Database ERDs when:**
- Adding new tables or columns
- Modifying foreign key relationships
- Adding/removing indexes or constraints
- Changing data types

**Update Authentication/Authorization Diagrams when:**
- Adding new policies or policy methods
- Changing authentication configuration
- Modifying role hierarchies or permissions
- Adding new protected resources

**Update Workflow Diagrams when:**
- Adding new application states
- Changing approval logic
- Modifying business rules
- Adding new user journeys or processes

### Update Process
1. Modify the Mermaid diagram code in the relevant `.md` file
2. Update the "Last Updated" date at the top of the file
3. Update explanatory text to reflect changes
4. Test rendering in GitHub or Mermaid Live Editor
5. Commit changes with descriptive commit message
6. Update this README.md if new diagrams are added

---

## 🎨 Diagram Style Guide

For consistency across all diagrams:

### Naming Conventions
- **Table Names**: Use exact database names (e.g., `dog_application`, not `DogApplication`)
- **Column Names**: Use camelCase as in database (e.g., `userId`, `dateCreated`)
- **Entity Names**: Match CakePHP model names (e.g., User, Dog, DogApplication)

### Visual Standards
- Use consistent cardinality notation in ERDs
- Label all relationships with meaningful descriptions
- Include legends for complex diagrams
- Keep diagrams focused (split large schemas into multiple diagrams)
- Use notes to highlight non-standard patterns

### Documentation Standards
- Include "Last Updated" date on every page
- Provide context and overview before showing diagrams
- Explain diagram components after showing visuals
- Reference source code files with line numbers when applicable
- Cross-link related documentation

---

## 📊 Documentation Statistics

| Category | Files | Diagrams | Status |
|----------|-------|----------|--------|
| **Architecture** | 4 | 9 | ✅ Complete |
| **Workflows** | 2 | 5 | ✅ Complete |
| **Development** | 2 | 4 | ✅ Complete |
| **Overview** | 1 | 0 | ✅ Complete |
| **Total** | **9** | **18** | - |

### Coverage Summary
- ✅ Database schema (complete)
- ✅ Authentication & authorization (complete)
- ✅ Adoption workflow (complete)
- ✅ Email notification system (complete)
- ✅ Testing patterns (complete)
- ✅ System architecture (complete)
- ✅ Admin user management (complete)
- ✅ Non-technical overview (complete)
- 📝 Deployment workflows (planned)
- 📝 API documentation (planned)

---

## 🤝 Contributing to Documentation

### Adding New Diagrams
1. Determine appropriate category (`architecture/`, `workflows/`, `development/`)
2. Create markdown file with descriptive name
3. Include all standard sections (title, date, overview, context, diagrams, explanations)
4. Test Mermaid syntax at [mermaid.live](https://mermaid.live)
5. Update this README.md to include the new diagram
6. Update CLAUDE.md with links if relevant

### Improving Existing Diagrams
1. Read the existing diagram and explanatory text
2. Make improvements (clarify relationships, add missing components, fix errors)
3. Update "Last Updated" date
4. Document what changed in your commit message
5. Ensure diagrams still render correctly

### Documentation Standards
- Write for developers who are new to the codebase
- Be accurate—verify against source code
- Be complete—don't leave out important details
- Be clear—explain non-obvious patterns and decisions
- Be maintainable—use consistent formatting and structure

---

## 📞 Questions or Issues?

If you find errors in the documentation or have suggestions for improvements:
1. Create an issue in the project repository
2. Tag with `documentation` label
3. Reference the specific diagram/file with issues
4. Provide suggested corrections or improvements

For questions about the codebase itself, consult:
- [CLAUDE.md](../CLAUDE.md) for project conventions
- CakePHP official documentation for framework questions
- Project README for setup and development instructions

---

## 📜 License

This documentation is part of the Hannah's Haus Cake project and inherits the project's license.

---

**Happy documenting! 📚✨**
