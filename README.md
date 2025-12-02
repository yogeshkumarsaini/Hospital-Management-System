# Hospital Management System (True Life Care Hospital)

A simple PHP-based Hospital Management System (HMS) frontend and backend skeleton. This project contains a public landing page, gallery, contact form and login entry-points for Patient, Doctor, and Admin. The application uses plain PHP, MySQL (mysqli) and Bootstrap for UI.

> Note: The project code currently contains raw SQL string concatenation (see contact form). Before using in production, follow the Security & Hardening section below.

## Table of Contents
- Overview
- Features
- Tech stack
- Prerequisites
- Installation & Setup
- Database (example)
- Configuration
- Run / Access
- File structure (high level)
- Security & Hardening
- Development notes & improvements
- Contributing
- License
- Contact

## Overview
This project is a hospital website with:
- A public landing page (home, about, gallery, contact).
- Contact form that stores messages in `tblcontactus`.
- Login links for Patient, Doctor and Admin sections (entry points: `hms/user-login.php`, `hms/doctor`, `hms/admin`).
- Pages content loaded from DB (e.g., `tblpage` has about, contact details).

## Features
- Public landing page with carousel and gallery
- Contact form (stores name, email, phone, message)
- Sections: Services, About, Gallery, Contact, Logins
- Bootstrap-based responsive UI

## Tech stack
- PHP (recommended >= 7.4)
- MySQL / MariaDB
- Apache or Nginx (LAMP / WAMP / XAMPP)
- Bootstrap, jQuery (frontend assets provided)

## Prerequisites
- PHP with mysqli extension enabled
- MySQL or MariaDB
- Web server (Apache recommended for local dev)
- Composer (optional, if adding libraries)

## Installation & Setup (local)
1. Place the project in your web root:
   - Example with XAMPP: `C:\xampp\htdocs\Hospital-Management-System` or `/opt/lampp/htdocs/Hospital-Management-System`
2. Create a new MySQL database for the project:
   - Example database name: `hms_db`
3. Import the database schema and sample data (if an SQL file is provided). If not, use the example SQL below to create required tables.
4. Update DB credentials in `hms/include/config.php`.
5. Open your browser and navigate to:
   - `http://localhost/Hospital-Management-System/` (adjust path to your setup)

## Example database (minimal)
If your project does not include a DB dump, create minimal required tables. Run the SQL below in your database:

```sql
-- Example minimal schema (run on your HMS database)
CREATE TABLE IF NOT EXISTS tblcontactus (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  contactno VARCHAR(50) NOT NULL,
  message TEXT,
  posting_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tblpage (
  id INT AUTO_INCREMENT PRIMARY KEY,
  PageType VARCHAR(50) NOT NULL,
  PageDescription TEXT,
  MobileNumber VARCHAR(50),
  Email VARCHAR(255),
  OpenningTime VARCHAR(255)
);

-- Example rows
INSERT INTO tblpage (PageType, PageDescription, MobileNumber, Email, OpenningTime)
VALUES ('aboutus', 'True Life Care Hospital is committed to providing quality care.', '+1-555-555-5555', 'info@example.com', 'Mon-Fri 9:00 AM - 6:00 PM');

INSERT INTO tblpage (PageType, PageDescription, MobileNumber, Email, OpenningTime)
VALUES ('contactus', 'Find us at 123 Health St, MedCity', '+1-555-555-5555', 'contact@example.com', 'Mon-Fri 9:00 AM - 6:00 PM');
```

Modify or extend tables to match the rest of your project's expectations (users, appointments, doctors, admin, etc).

## Configuration
Open `hms/include/config.php` and set your DB connection variables. Example format (your file may already have similar content):

```php
<?php
// hms/include/config.php (example)
$host = 'localhost';
$dbname = 'hms_db';
$dbuser = 'root';
$dbpass = ''; // set your DB password

$con = mysqli_connect($host, $dbuser, $dbpass, $dbname);
if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>
```

Restart your web server if needed.

## Run / Access
- Public site: `index.php`
- Contact form posts to the landing page (in your provided code: the insert runs on submit).
- Logins:
  - Patient login page: `hms/user-login.php`
  - Doctor login: `hms/doctor`
  - Admin login: `hms/admin`

## File structure (high level)
- index.php — landing page & contact form
- hms/
  - include/config.php — DB connection
  - user-login.php — patient login
  - doctor/ — doctor panel
  - admin/ — admin panel
- assets/
  - css/ — styles (Bootstrap, custom)
  - js/ — scripts (jQuery, Bootstrap, custom)
  - images/ — slider, gallery, avatars

Adjust structure as needed.

## Security & Hardening (important)
Your current code inserts form values into SQL directly and is vulnerable to SQL injection and other issues. Before deploying, implement the following:

- Use prepared statements (mysqli_stmt or PDO) to avoid SQL injection:
  - Example: mysqli_prepare + mysqli_stmt_bind_param or use PDO with prepared queries.
- Validate and sanitize user inputs (server-side).
- Use HTTPS and set secure cookies.
- For user authentication:
  - Store passwords with password_hash() and verify with password_verify().
  - Implement session management (session_regenerate_id) and proper session lifetime.
- Add CSRF protection on forms (tokens).
- Escape output in HTML to prevent XSS (e.g., htmlspecialchars()).
- Limit error output (do not expose DB errors to users).
- Use least-privileged DB user (no DROP/DELETE unless necessary).
- Set proper filesystem permissions for config files (do not make them world-readable).
- Consider rate-limiting and CAPTCHA on forms to prevent spam.

Example secured contact insert with mysqli prepared statement:

```php
$stmt = $con->prepare("INSERT INTO tblcontactus (fullname, email, contactno, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $mobileno, $dscrption);
$stmt->execute();
$stmt->close();
```

## Development notes & improvements
- Split logic from presentation (use MVC or at least separate includes for handlers).
- Use Composer and PSR-4 autoloading for larger projects.
- Add unit/integration tests for important flows.
- Add database migrations (e.g., Phinx or simple SQL scripts).
- Implement role-based access control (RBAC) for doctor/patient/admin.
- Use template engine or components to reduce duplication.
- Add logging (Monolog) for server-side errors.

## Contributing
1. Fork the project.
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Commit changes and open a PR with a clear description.
4. Run tests and make sure code meets project standards.

Please open an issue if you need help setting up the database schema or converting unsafe SQL to prepared statements.

## License
Add a LICENSE file at the project root. If unsure, you can use MIT:

```
MIT License
...
```

## Contact
For questions or assistance with setup and security hardening, contact the maintainer or open an issue in the repository.
