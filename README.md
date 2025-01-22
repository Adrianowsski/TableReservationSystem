# TableReservationSystem
The application allows users to make table reservations at a restaurant through a web browser. The system supports login, registration, reservation management (CRUD operations), and features a dedicated admin panel for managing reservations and tables.
Technologies:
Backend: PHP
Database: MySQL
Frontend: HTML, CSS (Bootstrap), JavaScript
Security: PHP sessions, data validation, CSRF tokens, password encryption

Application Architecture
User Modules
user/index.php:
User panel displaying personal data, active reservations, and links to manage reservations.
user/cancel.php:
Script for canceling reservations – verifies user authenticity and ensures the reservation belongs to the logged-in user.
Reservation Modules
reservations.php:
Page for selecting a date, time, number of people, and searching available tables. Once selected, the reservation is saved in the database.
Authorization Modules
login.php:
Login form that verifies user data and redirects appropriately (user or admin).
register.php:
Registration form for new users – validates input fields, encrypts passwords, and saves data to the database.
Admin Modules
admin/index.php:
Admin panel presenting statistics (e.g., number of tables, reservations, reservation status) and a list of upcoming reservations.
admin/reservations.php:
Managing reservations – filtering by date and status, and updating reservation statuses (e.g., confirming or canceling).
admin/tables.php, admin/edit_table.php, admin/delete_table.php:
Managing tables – adding, editing, and deleting table records.
Content Presentation Modules
menu.php:
Page presenting the restaurant menu (appetizers, main courses, desserts) with descriptions, photos, and prices.
index.php:
Welcome page displaying key information about the restaurant and login/registration buttons.
![Zrzut ekranu 2025-01-22 181641](https://github.com/user-attachments/assets/bbcf70a7-4b27-4575-ac39-9f6d03e3add5)

