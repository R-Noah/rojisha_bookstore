# Bookstore Web Application

This project is a PHP and MySQL web application developed for managing a bookstore catalog. It provides full CRUD functionality, multi-criteria search, AJAX autocomplete, user authentication, and secure coding practices. The interface is responsive and uses the Twig template engine for clean separation of logic and presentation.

## Features
- Create, read, update, and delete book records
- Search by multiple criteria (title, author, genre, year)
- AJAX-based title autocomplete
- User login with password hashing, CAPTCHA, and session protection
- Input sanitisation and output escaping
- Prepared statements for all database operations
- Responsive user interface with custom CSS
- Twig template engine for structured page layouts

## Technologies Used
- PHP 8+
- MySQL / MariaDB
- Twig Template Engine
- Composer
- HTML, CSS, JavaScript (Fetch API)

## Project Structure
config/ Database configuration
public/ Public-facing pages (index, login, add, edit, delete)
src/ Models, security, and helper functions
templates/ Twig templates for all rendered views
public/assets/ CSS and JavaScript
public/ajax/ AJAX endpoints
vendor/ Composer dependencies

sql
Copy code

## Database
Two tables are required:
- `books` – stores book information  
- `users` – stores login credentials (passwords hashed)

An admin user is inserted manually through SQL when setting up the database.

## Security
The application includes:
- Password hashing using password_hash  
- CAPTCHA on login  
- Session-based access control  
- Input filtering helpers  
- Output escaping through Twig auto-escape  
- Prepared statements to prevent SQL injection

## Deployment
The system runs locally or on the university mi-linux server.  


## Version Control
The project is tracked using Git with multiple feature branches and descriptive commit 