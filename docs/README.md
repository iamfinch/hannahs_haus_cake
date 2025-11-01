# Hannah's Haus Cake Documentation

**Last Updated:** 2025-11-01

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
│   └── auth-flow.md
├── workflows/            # Business process workflows
│   └── adoption-process.md
├── development/          # Development practices and patterns
│   └── (future content)
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

## 🎯 Quick Navigation

### For New Developers
Start here to understand the system:
1. [Core Domain ERD](architecture/database-erd-core.md) - Understand the main entities
2. [Authentication & Authorization Flow](architecture/auth-flow.md) - Learn the security model
3. [Adoption Workflow](workflows/adoption-process.md) - Understand the business process

### For Backend Developers
- [Core Domain ERD](architecture/database-erd-core.md) - Database relationships
- [User Profile ERD](architecture/database-erd-lookups.md) - Complete schema details
- [Authorization Flow](architecture/auth-flow.md) - Policy implementation

### For Product Managers / Business Analysts
- [Adoption Workflow](workflows/adoption-process.md) - Business process overview
- [Authorization Flow - Role Hierarchy](architecture/auth-flow.md#role-based-access-control) - User permissions

### For DevOps / Database Administrators
- [Core Domain ERD](architecture/database-erd-core.md) - Primary tables and indexes
- [User Profile ERD](architecture/database-erd-lookups.md) - Complete schema with foreign keys

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
| **Architecture** | 3 | 7 | ✅ Complete |
| **Workflows** | 1 | 3 | ✅ Complete |
| **Development** | 0 | 0 | 📝 Planned |
| **Total** | **4** | **10** | - |

### Coverage Summary
- ✅ Database schema (complete)
- ✅ Authentication & authorization (complete)
- ✅ Adoption workflow (complete)
- 📝 Testing patterns (planned)
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
