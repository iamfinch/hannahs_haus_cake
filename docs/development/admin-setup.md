# Admin User Setup Guide

**Last Updated:** 2025-11-02

This guide explains how to create and manage administrator user accounts for the Hannah's Haus Cake dog adoption system.

---

## Table of Contents

- [Overview](#overview)
- [Admin User Privileges](#admin-user-privileges)
- [Creating Admin Users](#creating-admin-users)
- [Verification Steps](#verification-steps)
- [Managing Admin Users](#managing-admin-users)
- [Troubleshooting](#troubleshooting)
- [Future Enhancements](#future-enhancements)

---

## Overview

### What is an Admin User?

Admin users are staff members with elevated privileges who can manage adoption applications, dog listings, and view applicant information. The admin flag (`isAdmin`) in the users table determines whether a user has administrative access.

### Current Implementation

**Status:** Manual database update (SQL script)
**Location:** `private/sql/create-admin-user.sql`
**Future Plan:** Automated seeding system (planned enhancement)

**Why Manual?** The current approach provides:
- ✅ Explicit control over admin privilege grants
- ✅ Clear audit trail (modified timestamp)
- ✅ Flexibility during early development
- ✅ No accidental admin creation

**Limitations:**
- ❌ Requires database access
- ❌ Manual process (not automated)
- ❌ Requires existing user account first

---

## Admin User Privileges

### What Can Admin Users Do?

**Adoption Application Management:**
- View all pending, approved, and rejected applications
- Access admin dashboard at `/dog-application/admin-dashboard`
- Approve adoption applications (marks dog as adopted, rejects competing apps)
- Reject adoption applications with empathetic notifications
- View complete applicant information (housing, family details, pet history)

**Dog Management:**
- Add new dogs to the adoption system
- Edit existing dog listings
- Delete dog listings
- Mark dogs as retired or adopted
- Upload and manage dog photos

**User Management:**
- View all user profiles
- Access detailed user information for application review
- See complete adoption history for each user

**Email Notifications:**
- Admin receives automatic notifications when new applications are submitted
- All admin actions trigger appropriate email notifications to applicants

### What Can't Admin Users Do?

- Modify other users' passwords (users must use password reset)
- Delete user accounts (not currently implemented)
- Access database directly (requires separate database credentials)
- Grant admin privileges to other users (requires SQL script)

---

## Creating Admin Users

### Prerequisites

1. ✅ **User account must already exist** - Create via web registration first
2. ✅ **Docker services running** - `docker-compose up -d`
3. ✅ **Database migrations completed** - `docker exec myapp-webserver bin/cake migrations migrate`
4. ✅ **User must have verified their email** (if email verification is enabled)

### Step-by-Step Instructions

#### Method 1: Using SQL Script (Recommended)

This is the official method documented in the SQL script.

**Step 1: Locate the Script**
```bash
# Script location
private/sql/create-admin-user.sql
```

**Step 2: Find the User ID**

First, identify which user should become an admin:
```bash
docker exec -it hannahs_haus_db mysql -u my_user -psecret my_app
```

Then run:
```sql
SELECT id, email, fname, lname, isAdmin
FROM users
WHERE email = 'admin@hannahshaus.com';
```

Note the `id` value for the next step.

**Step 3: Edit the Script**

Open `private/sql/create-admin-user.sql` and update:
- Line with `WHERE email = 'admin@hannahshaus.com'` → your admin's email
- All instances of `WHERE id = 1` → the actual user ID from Step 2

**Step 4: Run the Script**
```bash
docker exec -i hannahs_haus_db mysql -u my_user -psecret my_app < private/sql/create-admin-user.sql
```

**Step 5: Verify**

Check the output shows:
- ✅ User found with matching email
- ✅ 1 row updated
- ✅ User now shows `isAdmin = 1`

---

#### Method 2: Interactive MySQL Session

If you prefer step-by-step execution:

**Step 1: Access MySQL**
```bash
docker exec -it hannahs_haus_db mysql -u my_user -psecret my_app
```

**Step 2: Find User**
```sql
SELECT id, email, fname, lname, isAdmin FROM users WHERE email = 'your-admin@example.com';
```

**Step 3: Update to Admin**
```sql
UPDATE users SET isAdmin = 1, modified = NOW() WHERE id = 123;  -- Replace 123 with actual ID
```

**Step 4: Verify**
```sql
SELECT id, email, fname, lname, isAdmin FROM users WHERE id = 123;
```

**Step 5: Exit**
```sql
exit
```

---

#### Method 3: Via CakePHP Console (Alternative)

For those familiar with CakePHP:

**Step 1: Access PHP Container**
```bash
docker exec -it myapp-webserver bash
```

**Step 2: Open CakePHP Console**
```bash
bin/cake console
```

**Step 3: Run Commands**
```php
$usersTable = $this->fetchTable('Users');
$user = $usersTable->get(123);  // Replace 123 with user ID
$user->isAdmin = true;
$usersTable->save($user);
```

**Step 4: Verify**
```php
$admin = $usersTable->get(123);
echo "Is Admin: " . ($admin->isAdmin ? 'Yes' : 'No');
exit
```

---

## Verification Steps

### 1. Database Verification

Confirm the database was updated:
```bash
docker exec -it hannahs_haus_db mysql -u my_user -psecret my_app -e \
  "SELECT id, email, isAdmin, modified FROM my_app.users WHERE isAdmin = 1;"
```

Expected output:
```
+----+-------------------------+---------+---------------------+
| id | email                   | isAdmin | modified            |
+----+-------------------------+---------+---------------------+
|  1 | admin@hannahshaus.com   |       1 | 2025-11-02 15:30:00 |
+----+-------------------------+---------+---------------------+
```

### 2. Application Verification

**Step 1: Log Out** (if currently logged in)
```
http://localhost/users/logout
```

**Step 2: Log In as Admin**
```
http://localhost/users/login
Email: admin@hannahshaus.com
Password: [their password]
```

**Step 3: Access Admin Dashboard**
```
http://localhost/dog-application/admin-dashboard
```

**Expected Result:**
- ✅ No "Permission Denied" error
- ✅ Dashboard loads with pending applications
- ✅ Statistics displayed (Pending, Approved, Rejected counts)
- ✅ Approve/Reject buttons visible on pending applications

### 3. Session Refresh

If admin status doesn't work immediately:

1. **Clear browser cache and cookies**
2. **Log out completely**: `http://localhost/users/logout`
3. **Close all browser tabs** for the application
4. **Log back in** as the admin user
5. **Try accessing admin dashboard again**

### 4. Authorization Check

Test that regular users still can't access admin features:

1. Log in as a non-admin user
2. Try accessing: `http://localhost/dog-application/admin-dashboard`
3. **Expected:** "You do not have permission" message

---

## Managing Admin Users

### Listing All Admin Users

View all current administrators:
```bash
docker exec -it hannahs_haus_db mysql -u my_user -psecret my_app -e \
  "SELECT id, email, fname, lname, created, modified FROM my_app.users WHERE isAdmin = 1 ORDER BY created;"
```

### Removing Admin Status

To revoke admin privileges from a user:

**Method 1: SQL Update**
```bash
docker exec -it hannahs_haus_db mysql -u my_user -psecret my_app -e \
  "UPDATE my_app.users SET isAdmin = 0, modified = NOW() WHERE id = 123;"
```

**Method 2: Interactive Session**
```bash
docker exec -it hannahs_haus_db mysql -u my_user -psecret my_app
```

```sql
UPDATE users SET isAdmin = 0, modified = NOW() WHERE id = 123;
SELECT id, email, isAdmin FROM users WHERE id = 123;  -- Verify
exit
```

**Important:** User must log out and log back in for changes to take effect.

### Best Practices

1. **Limit Admin Count** - Only grant admin status to trusted staff members
2. **Document Changes** - Keep a log of when admin privileges are granted/revoked
3. **Regular Audits** - Periodically review the list of admin users
4. **Use Modified Timestamp** - Track when admin status was last changed
5. **Test in Development** - Always test admin creation process in dev before production

---

## Troubleshooting

### Problem: "You do not have permission to access the admin dashboard"

**Possible Causes:**
1. User is not marked as admin in database
2. User session hasn't refreshed after admin status granted
3. Authorization policy is preventing access

**Solutions:**

**Check 1: Verify Database**
```bash
docker exec -it hannahs_haus_db mysql -u my_user -psecret my_app -e \
  "SELECT id, email, isAdmin FROM my_app.users WHERE email = 'your-admin@example.com';"
```
Should show `isAdmin = 1`.

**Check 2: Refresh Session**
- Log out: `http://localhost/users/logout`
- Clear browser cache and cookies
- Log back in

**Check 3: Verify Policy**
```bash
docker exec myapp-webserver cat src/Policy/DogApplicationPolicy.php | grep -A 5 "adminDashboard"
```
Should show `return $user->isAdmin;` or similar.

---

### Problem: "Access denied for user 'my_user'@'%'"

**Cause:** Database credentials incorrect

**Solution:**
1. Check `config/app.php` for correct database credentials
2. Verify DATABASE_URL environment variable:
   ```bash
   docker exec myapp-webserver env | grep DATABASE_URL
   ```
3. Ensure database container is running:
   ```bash
   docker ps | grep hannahs_haus_db
   ```

---

### Problem: "Table 'my_app.users' doesn't exist"

**Cause:** Database migrations haven't been run

**Solution:**
```bash
docker exec myapp-webserver bin/cake migrations migrate
```

---

### Problem: SQL script reports "0 rows affected"

**Cause:** WHERE clause doesn't match any users

**Solutions:**

**Check 1: Verify User Exists**
```sql
SELECT * FROM users WHERE email = 'admin@hannahshaus.com';
```

**Check 2: Check User ID**
```sql
SELECT id FROM users WHERE email = 'admin@hannahshaus.com';
-- Use this ID in UPDATE statement
```

**Check 3: Case Sensitivity**
Email addresses are case-insensitive in most databases, but verify:
```sql
SELECT * FROM users WHERE LOWER(email) = LOWER('Admin@HannahsHaus.com');
```

---

### Problem: Changes don't appear in application

**Cause:** Session caching or browser cache

**Solutions:**

**Step 1: Clear Application Cache**
```bash
docker exec myapp-webserver bin/cake cache clear_all
```

**Step 2: Restart PHP-FPM**
```bash
docker-compose restart php-fpm
```

**Step 3: Clear Browser Data**
- Clear cookies for localhost
- Clear cached images and files
- Or use Incognito/Private browsing mode

**Step 4: Force Session Refresh**
- Log out: `/users/logout`
- Close all browser tabs
- Log back in

---

## Future Enhancements

### Planned: Seeding System

**Current Status:** Manual SQL script process
**Future Implementation:** CakePHP seeding system

**Benefits of Seeding System:**
- ✅ Automated admin creation during setup
- ✅ Environment-specific seeds (dev, staging, production)
- ✅ Idempotent (safe to run multiple times)
- ✅ Version-controlled seed data
- ✅ Default dev admin credentials
- ✅ Integration with CI/CD deployment

**Planned Structure:**
```
config/Seeds/
├── AdminUsersSeed.php        # Creates default admin users
├── DevelopmentSeed.php        # Dev-only test data
└── ProductionSeed.php         # Production admin setup
```

**Example Usage (Future):**
```bash
# Create admin users
docker exec myapp-webserver bin/cake migrations seed --seed AdminUsersSeed

# Create dev environment data
docker exec myapp-webserver bin/cake migrations seed --seed DevelopmentSeed
```

**Timeline:** To be implemented when development requirements are finalized.

---

### Planned: Admin Management UI

**Future Feature:** Web-based admin user management

**Planned Capabilities:**
- Create admin users from web interface
- View list of all administrators
- Grant/revoke admin privileges
- Audit log of admin actions
- Role-based permissions (beyond simple admin flag)

**Security Considerations:**
- Only super-admins can manage other admins
- Two-factor authentication for admin accounts
- Activity logging for all admin operations
- IP whitelist for admin access

**Timeline:** Post-MVP enhancement

---

## Related Documentation

- **[SQL Scripts Directory](../../private/sql/README.md)** - SQL script usage and safety guidelines
- **[Authorization Flow](../architecture/auth-flow.md)** - Policy-based authorization system
- **[Testing Patterns](testing-patterns.md)** - How to test authorization policies
- **[Main README](../../README.md)** - Docker commands and troubleshooting

---

## Security Considerations

### Admin Account Security

🔒 **Strong Passwords Required**
- Admin accounts should use strong, unique passwords
- Consider implementing 2FA for admin users (future enhancement)
- Regularly rotate admin passwords

🔒 **Principle of Least Privilege**
- Only grant admin status to users who need it
- Remove admin status when staff members leave or change roles
- Consider implementing role-based access control for granular permissions

🔒 **Audit Trail**
- The `modified` timestamp tracks when admin status was changed
- Consider implementing comprehensive admin action logging
- Regularly review admin user list

🔒 **Production Security**
- Never use default or weak passwords for admin accounts
- Limit database access to authorized personnel only
- Use environment variables for sensitive configuration
- Implement IP whitelisting for admin access (future enhancement)

---

## Questions or Issues?

If you encounter problems creating admin users:
1. Review the [Troubleshooting](#troubleshooting) section above
2. Check the [SQL Scripts README](../../private/sql/README.md)
3. Consult the [main README troubleshooting](../../README.md#troubleshooting)
4. Contact your database administrator or development team lead

---

**Last Updated:** 2025-11-02
