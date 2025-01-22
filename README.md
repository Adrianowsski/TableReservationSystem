Table Reservation System – Restaurant

The application enables users to make table reservations in a restaurant via a web browser. The system supports user login, registration, reservation management (CRUD operations), and includes a dedicated admin panel for managing reservations and tables.

Technologies:

Backend: PHP

Database: MySQL

Frontend: HTML, CSS (Bootstrap), JavaScript

Security: PHP sessions, data validation, CSRF tokens, password encryption

![Zrzut ekranu 2025-01-22 173147](https://github.com/user-attachments/assets/dc013060-a58d-4457-8d05-483e6ffb2502)

Application Architecture

Projekt został podzielony na następujące moduły:

Moduły Użytkownika

● user/index.php:

User panel displaying personal data, active reservations, and links for reservation management.

![Zrzut ekranu 2025-01-22 175546](https://github.com/user-attachments/assets/71c3ebb0-ae26-4420-aa5b-2d8da418e39a)

● user/cancel.php:

A script for canceling reservations, verifying the user's authenticity and ensuring the reservation belongs to the logged-in user.

Reservation Modules

● reservations.php:
A page allowing users to select a date, time, and number of people, then search for available tables. Upon selecting a table, the reservation is saved in the database.

![Zrzut ekranu 2025-01-22 175609](https://github.com/user-attachments/assets/94a683a5-2bb2-4e95-a441-1ac0812202fc)

Authentication Modules

● login.php:

A login form that verifies user credentials and redirects to the appropriate panel (user or admin).

![Zrzut ekranu 2025-01-22 175518](https://github.com/user-attachments/assets/c415d49d-82c0-436e-8364-cc2a5f00ae86)

register.php:

A registration form for new users with field validation, password encryption, and database entry.

![Zrzut ekranu 2025-01-22 175439](https://github.com/user-attachments/assets/59305b5f-73f3-4821-841e-124aeaadac90)

Admin Modules

● admin/index.php:

Admin panel displaying statistics (e.g., total tables, reservations, reservation status) and a list of upcoming reservations.

![Zrzut ekranu 2025-01-22 175652](https://github.com/user-attachments/assets/699c8b8a-a50d-431f-a79d-cc39a5dcd5f6)

admin/reservations.php:

Reservation management with filtering options (by date and status) and the ability to change reservation statuses (e.g., confirm or cancel).

![Zrzut ekranu 2025-01-22 180733](https://github.com/user-attachments/assets/9761b8c8-ac06-4773-82bc-c887db8e4981)

admin/tables.php, admin/edit_table.php, admin/delete_table.php:

Table management modules for adding, editing, and deleting table records.

![Zrzut ekranu 2025-01-22 175709](https://github.com/user-attachments/assets/d3d7f563-2294-44de-81c4-431ea82d1f16)

Content Presentation Modules

● menu.php:

A page displaying the restaurant menu (appetizers, main courses, desserts) with descriptions, images, and prices.


![Zrzut ekranu 2025-01-22 173109](https://github.com/user-attachments/assets/47f7a558-4a91-4a98-8f54-2f4b1445d322)

![Zrzut ekranu 2025-01-22 173118](https://github.com/user-attachments/assets/c85544b6-a7a0-4943-b420-a4d5c9ec9156)

index.php:

A welcome page with general restaurant information and login/registration buttons.

![Zrzut ekranu 2025-01-22 173147](https://github.com/user-attachments/assets/9643c359-8760-4005-abba-a84879724232)

Database Structure

The MySQL database consists of at least three primary tables:

3.1. users

● Fields:
○ id (PK)
○ first_name
○ last_name
○ email
○ password
○ role (wartość: admin lub user)

3.2. reservations

● Fields:
○ id
○ user_id (FK - powiązanie z users)
○ table_id (FK - powiązanie z tables)
○ reservation_date
○ reservation_time
○ num_of_people
○ status (wartości: pending, confirmed, cancelled)

3.3. tables

● Fields:
○ id
○ table_name (lub numer stolika)
○ seats (liczba miejsc)
○ location (lokalizacja w restauracji)


![Zrzut ekranu 2025-01-22 175304](https://github.com/user-attachments/assets/f025f28a-678a-4683-b156-ecdd7205b95a)

![Zrzut ekranu 2025-01-22 173441](https://github.com/user-attachments/assets/144301f3-b7c6-4599-ac5b-10cc9de6d046)

System Flow and Functionalities

4.1. Registration and Login

● Registration (register.php):

The user fills out the registration form. Data is validated using helper functions (includes/validation.php). After validation, user data (with encrypted password) is saved in the users table.

![Zrzut ekranu 2025-01-22 175439](https://github.com/user-attachments/assets/eebebadb-fb9e-4e39-ad49-414e250b7cad)

Login (login.php):

The user logs in with an email and password. Passwords are verified using password_verify. Session variables (e.g., user_id and role) are set, and the user is redirected to the appropriate panel.


![Zrzut ekranu 2025-01-22 175518](https://github.com/user-attachments/assets/b5bd5859-cf3f-4157-9b40-483cd7f15309)

Reservation Management

● User Side:

○ Logged-in users can view active reservations in their panel (user/index.php).

○ The reservation form (reservations.php) allows users to select a date, time, and number of people to find available tables. If a table is available, the reservation is saved in the database.

○ Users can cancel reservations via user/cancel.php, changing the reservation status to cancelled.

![Zrzut ekranu 2025-01-22 175546](https://github.com/user-attachments/assets/65cddda7-e769-460c-b1b4-9a004f3ea2a9)

![Zrzut ekranu 2025-01-22 175609](https://github.com/user-attachments/assets/36167c42-592d-4a04-b412-edee6c968019)

![Zrzut ekranu 2025-01-22 181440](https://github.com/user-attachments/assets/8b44b82f-38b0-4e2e-be33-aaf276383fe3)

![Zrzut ekranu 2025-01-22 181500](https://github.com/user-attachments/assets/29e9840e-3231-4b73-b672-237f635f478a)

Admin Side:

Admins can filter, view, and modify reservation statuses (e.g., confirm or cancel) using modules in the admin panel (admin/reservations.php).

![Zrzut ekranu 2025-01-22 173016](https://github.com/user-attachments/assets/65956e94-2df1-4208-a1e1-cd4154162a78)

![Zrzut ekranu 2025-01-22 181641](https://github.com/user-attachments/assets/80ebe680-7db1-4e40-993e-28573c34039c)

![Zrzut ekranu 2025-01-22 181630](https://github.com/user-attachments/assets/3f25170d-e62b-4986-be89-36b9162db51a)

Table Management (CRUD)

Admin modules (admin/tables.php, admin/edit_table.php, admin/delete_table.php) allow the administrator to create, edit, and delete table records.

![Zrzut ekranu 2025-01-22 175709](https://github.com/user-attachments/assets/efbf5d48-a890-4641-9eeb-bbbc2d81e778)
![Zrzut ekranu 2025-01-22 175729](https://github.com/user-attachments/assets/8bc6199a-6afc-4310-a39c-9f6cc712ff22)

Admin Panel

The admin panel (admin/index.php) displays key statistics, including:

● Total number of tables

● Total number of reservations

● Breakdown of reservations by status

● List of upcoming reservations

![Zrzut ekranu 2025-01-22 175652](https://github.com/user-attachments/assets/a9b178cf-94c7-4e11-b173-717e05dc820a)

Shared Modules and Helper Mechanisms

5.1. Configuration

● config/config.php:
Contains database connection settings (host, database name, user, password) and other application parameters (e.g., opening hours).

 Helper Functions (includes/functions.php)

● ensureSessionStarted() – Initializes a session if not already started.

● ensureAdmin() / ensureUser() – Verifies if the user is logged in and has the appropriate role.

● getCsrfToken() i checkCsrfToken($token) – Generate and verify CSRF tokens for form security.

● renderReservationStatus($status) – Renders reservation statuses with corresponding badges and colors (e.g., green for confirmed, red for canceled).

● getPaginationOffset($page, $limit) – Calculates SQL offset for pagination.


System Komunikatów (includes/messages.php)
● setMessage($type, $message) – Ustawia komunikaty dla użytkownika (sukces,
błąd).
● displayMessages() – Wyświetla zapisane komunikaty w formie alertów. 


Data Validation (includes/validation.php)

● validateEmail($email) – Validates email format.

● validateText($text) – Ensures text fields are not empty.

● validatePassword($password) – Ensures passwords meet minimum length requirements (e.g., 8 characters).


Time Option Generation (includes/time_options.php)

● generateTimeOptions($selectedTime = "") – Generates a list of time options (from 13:00 to 22:00, in 15-minute intervals) for the reservation form.

Summary

The project is modularly designed, with a clear division into user, reservation, authentication, and admin modules. Each key element is supported by appropriate helper functions, and data security and validation are implemented following best practices (CSRF, sessions, password encryption). This documentation, along with the provided screenshots, offers a comprehensive description of the application's functionality and structure, simplifying both project evaluation and future development.

