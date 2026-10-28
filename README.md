# BookPrinters & ShreePublications — Production Management Portal

A PHP + MySQL portal for tracking each book order through five production stages:
1. Printing — Bhoomika Mahawar
2. Binding — Ashok Kumar Saini
3. Cutting — Rahul Saini
4. Lamination — Rahul Saini
5. Packing — Rahul Saini

Lamination is placed before packing in the production flow so that finished covers/books can be packed after processing.

## Features
- Admin dashboard with total jobs, pending stages, in-progress stages, completion counts and recent activity.
- Create and edit book production jobs with client, quantity, due date, priority and notes.
- Employee accounts with stage-based access.
- Employees can update status, completed copy quantity and notes/issues.
- Stage completion history and production journey per job.
- Responsive layout for desktop, tablet and mobile.
- Password hashing, prepared SQL statements, session regeneration and CSRF protection.

## Requirements
- PHP 8.1+ (8.2+ recommended), PDO MySQL extension
- MySQL 5.7+ / MariaDB 10.4+
- HTTPS recommended (enable SSL on your hosting)

## Hostinger installation
1. Create a MySQL database and database user in Hostinger hPanel.
2. Open phpMyAdmin and import `schema.sql` into that database.
3. Edit `config.php` and fill in DB_NAME, DB_USER and DB_PASS. Hostinger database names/users often have a prefix; copy the exact values from hPanel.
4. Upload the contents of this folder into a folder such as `public_html/production/`.
5. Visit `https://your-domain/production/setup.php` and create the admin account. Use a unique password of at least 10 characters.
6. Sign in at `https://your-domain/production/`.
7. **Immediately delete `setup.php` from the server after creating the admin.**
8. In Employees & access, create accounts for Bhoomika Mahawar, Ashok Kumar Saini and Rahul Saini. Use separate accounts for Rahul's stages if you want each login to show only one stage. Suggested usernames:
   - `bhoomika.print` — Printing
   - `ashok.binding` — Binding
   - `rahul.cutting` — Cutting
   - `rahul.lamination` — Lamination
   - `rahul.packing` — Packing

   Each account needs its own password of at least 10 characters. Set passwords yourself and share them privately. Do not send passwords in a public group.
9. Test with a demo book job and test each employee login before using live production data.

## Important notes
- Do not share your hosting or database password with employees.
- Back up the database regularly.
- Employee accounts can see and update only their assigned stage; admin can view all stages and manage users/jobs.
- A stage cannot be marked Completed until the completed quantity equals the job's total order quantity. For partial work, choose In progress and enter the completed quantity.
- This is a deployable starter portal, not yet integrated with your existing BookPrinters/ShreePublications website, Razorpay, inventory, or WhatsApp notifications.
- Before production use, confirm that your hosting has HTTPS enabled and that `setup.php` has been deleted.
