# Healthy Bite

Healthy Bite is a PHP and MySQL QR-based digital menu and table-ordering project for BCA Semester V.

## Stage 1 status

This repository currently contains the custom PHP MVC foundation, restaurant owner registration/login, protected dashboard, restaurant profile update, CSRF protection, and the first MySQL schema.

## Local setup (XAMPP)

1. Copy this folder to `C:\xampp\htdocs\healthy-bite`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Create/import the database using `database/schema/001_stage_1.sql` in phpMyAdmin.
4. Update database environment variables if your MySQL credentials differ from the defaults listed in `config/database.php`.
5. Open `http://localhost/healthy-bite/public/`.

For a cleaner URL, configure an Apache virtual host whose document root is the `public/` directory.

## Stage 1 demo flow

1. Open the registration page and register a restaurant owner account.
2. Sign in with the new owner email and password.
3. Update restaurant profile details from the dashboard.
4. Sign out and confirm protected dashboard pages redirect to login.

## Documentation

Project and college documents are in [docs/README.md](docs/README.md).
