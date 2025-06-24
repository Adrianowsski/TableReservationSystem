[![PHP](https://img.shields.io/badge/PHP-8.2-blue)](https://www.php.net/) [![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)](https://www.mysql.com/) [![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)](https://getbootstrap.com/) [![License: MIT](https://img.shields.io/badge/License-MIT-green)](LICENSE)

# 🍽️ Table Reservation System – Restaurant

A lightweight **PHP & MySQL** web app that lets guests reserve tables online while giving staff an easy‑to‑use admin panel for daily operations.

---

## 📌 Table of Contents

* [🚀 Overview](#🚀-overview)
* [✨ Features](#✨-features)
* [🛠️ Tech Stack](#🛠️-tech-stack)
* [🏗️ Project Structure](#🏗️-project-structure)
* [⚙️ Installation](#⚙️-installation)
* [🔧 Configuration](#🔧-configuration)
* [▶️ Running the App](#▶️-running-the-app)
* [📸 Screenshots](#📸-screenshots)
* [📄 License](#📄-license)

---

## 🚀 Overview

| Role      | Capabilities                                                                                              |
| --------- | --------------------------------------------------------------------------------------------------------- |
| **Guest** | - Search for available tables  <br> - Book & cancel reservations  <br> - View personal bookings & profile |
| **Admin** | - Approve / cancel reservations  <br> - CRUD of tables  <br> - Dashboard KPIs & statistics                |

The app follows a **modular MVC‑lite** structure (no framework), secured with sessions, CSRF tokens & password hashing.

---

## ✨ Features

1. **Authentication** – registration & login with encrypted passwords (password\_hash).
2. **Reservation Search** – date, time & party‑size filter; only free tables returned.
3. **Reservation CRUD** – users can create/cancel; admins can confirm/deny.
4. **Admin Dashboard** – total tables, reservations, status breakdown, upcoming list.
5. **Table Management** – add/edit/delete tables with seats & location.
6. **Restaurant Menu** – static menu page with images & prices.
7. **Security** – CSRF tokens, prepared statements, validation helpers.

---

## 🛠️ Tech Stack

| Layer        | Technology                 |
| ------------ | -------------------------- |
| **Backend**  | PHP 8.2 (PDO)              |
| **Database** | MySQL 8 (InnoDB)           |
| **Frontend** | HTML 5, Bootstrap 5, JS    |
| **Security** | PHP Sessions, CSRF, bcrypt |
| **Charts**   | Chart.js (dashboard stats) |

---

## 🏗️ Project Structure

```text
/ (docroot)
├─ admin/
│  ├─ index.php          # dashboard
│  ├─ reservations.php   # manage bookings
│  ├─ tables.php         # tables list
│  ├─ edit_table.php
│  └─ delete_table.php
├─ user/
│  ├─ index.php          # user panel
│  └─ cancel.php         # cancel booking
├─ includes/
│  ├─ config.php         # DB creds & constants
│  ├─ functions.php      # helper utilities
│  ├─ validation.php     # input validation
│  ├─ messages.php       # flash messages
│  └─ time_options.php   # 15‑min slot generator
├─ reservations.php      # search & book page
├─ menu.php              # restaurant menu
├─ login.php
├─ register.php
└─ assets/               # CSS, JS, images
```

---

## ⚙️ Installation

### 🔑 Prerequisites

* **PHP ≥ 8.2** with PDO MySQL ext.
* **MySQL 8** (or MariaDB 10.6+)
* Apache / Nginx configured with `DocumentRoot` pointing to repo root.

### 🏃‍♂️ Quick Start

```bash
# 1  Clone repository
 git clone https://github.com/YourUsername/TableReservationSystem.git
 cd TableReservationSystem

# 2  Import database
 mysql -u root -p < database/schema.sql

# 3  Configure creds
 cp includes/config.sample.php includes/config.php
 nano includes/config.php   # DB_USER, DB_PASS, OPENING_HOURS, etc.
```

---

## 🔧 Configuration

`includes/config.php` example:

```php
return [
  'db' => [
    'host' => '127.0.0.1',
    'name' => 'restaurant',
    'user' => 'root',
    'pass' => 'secret',
    'charset' => 'utf8mb4',
  ],
  'opening_hours' => [
    'start' => '13:00',
    'end'   => '22:00',
  ],
];
```

---

## ▶️ Running the App

1. Point your web server to the project root.
2. Browse to `http://localhost/index.php` → Register or Login.
3. Access admin panel at `http://localhost/admin/` (requires `role = 'admin'`).

> **Dev server** – If you don’t have Apache, run:
> `php -S localhost:8000`  and open `http://localhost:8000`.

---

## 📸 Screenshots

> Wszystkie zrzuty ekranu znajdują się w `assets/screenshots/` lub na dołączonych
> linkach GitHub attachments. Poniżej pełna galeria (26 pozycji) pogrupowana
> tematycznie.

### 1 · Architecture & Landing

| # | Screenshot                                                                           | Description                 |
| - | ------------------------------------------------------------------------------------ | --------------------------- |
| 1 | ![](https://github.com/user-attachments/assets/dc013060-a58d-4457-8d05-483e6ffb2502) | System architecture diagram |
| 2 | ![](https://github.com/user-attachments/assets/9643c359-8760-4005-abba-a84879724232) | Public landing page         |

### 2 · Authentication

| # | Screenshot                                                                           | Description             |
| - | ------------------------------------------------------------------------------------ | ----------------------- |
| 3 | ![](https://github.com/user-attachments/assets/c415d49d-82c0-436e-8364-cc2a5f00ae86) | Login form              |
| 4 | ![](https://github.com/user-attachments/assets/b5bd5859-cf3f-4157-9b40-483cd7f15309) | Login error banner      |
| 5 | ![](https://github.com/user-attachments/assets/59305b5f-73f3-4821-841e-124aeaadac90) | Registration form       |
| 6 | ![](https://github.com/user-attachments/assets/eebebadb-fb9e-4e39-ad49-414e250b7cad) | Registration validation |

### 3 · User Dashboard

| #  | Screenshot                                                                           | Description                     |
| -- | ------------------------------------------------------------------------------------ | ------------------------------- |
| 7  | ![](https://github.com/user-attachments/assets/71c3ebb0-ae26-4420-aa5b-2d8da418e39a) | User panel overview             |
| 8  | ![](https://github.com/user-attachments/assets/65cddda7-e769-460c-b1b4-9a004f3ea2a9) | Active reservations list        |
| 9  | ![](https://github.com/user-attachments/assets/8b44b82f-38b0-4e2e-be33-aaf276383fe3) | Reservation card details        |
| 10 | ![](https://github.com/user-attachments/assets/29e9840e-3231-4b73-b672-237f635f478a) | Cancel reservation confirmation |

### 4 · Reservation Flow

| #  | Screenshot                                                                           | Description                        |
| -- | ------------------------------------------------------------------------------------ | ---------------------------------- |
| 11 | ![](https://github.com/user-attachments/assets/94a683a5-2bb2-4e95-a441-1ac0812202fc) | Search form (date · time · people) |
| 12 | ![](https://github.com/user-attachments/assets/36167c42-592d-4a04-b412-edee6c968019) | Available tables list              |

### 5 · Admin – Dashboard & Reservations

| #  | Screenshot                                                                           | Description               |
| -- | ------------------------------------------------------------------------------------ | ------------------------- |
| 13 | ![](https://github.com/user-attachments/assets/699c8b8a-a50d-431f-a79d-cc39a5dcd5f6) | Admin dashboard KPIs      |
| 14 | ![](https://github.com/user-attachments/assets/9761b8c8-ac06-4773-82bc-c887db8e4981) | Reservations management   |
| 15 | ![](https://github.com/user-attachments/assets/80ebe680-7db1-4e40-993e-28573c34039c) | Reservation filters       |
| 16 | ![](https://github.com/user-attachments/assets/3f25170d-e62b-4986-be89-36b9162db51a) | Update reservation status |

### 6 · Admin – Tables

| #  | Screenshot                                                                           | Description               |
| -- | ------------------------------------------------------------------------------------ | ------------------------- |
| 17 | ![](https://github.com/user-attachments/assets/d3d7f563-2294-44de-81c4-431ea82d1f16) | Tables list               |
| 18 | ![](https://github.com/user-attachments/assets/efbf5d48-a890-4641-9eeb-bbbc2d81e778) | Edit table dialog         |
| 19 | ![](https://github.com/user-attachments/assets/8bc6199a-6afc-4310-a39c-9f6cc712ff22) | Delete table confirmation |

### 7 · Menu Pages

| #  | Screenshot                                                                           | Description           |
| -- | ------------------------------------------------------------------------------------ | --------------------- |
| 20 | ![](https://github.com/user-attachments/assets/47f7a558-4a91-4a98-8f54-2f4b1445d322) | Menu page – section 1 |
| 21 | ![](https://github.com/user-attachments/assets/c85544b6-a7a0-4943-b420-a4d5c9ec9156) | Menu page – section 2 |

### 8 · Database & Misc

| #  | Screenshot                                                                           | Description                  |
| -- | ------------------------------------------------------------------------------------ | ---------------------------- |
| 22 | ![](https://github.com/user-attachments/assets/f025f28a-678a-4683-b156-ecdd7205b95a) | Database ERD                 |
| 23 | ![](https://github.com/user-attachments/assets/144301f3-b7c6-4599-ac5b-10cc9de6d046) | Reservation status flowchart |
| 24 | ![](https://github.com/user-attachments/assets/65956e94-2df1-4208-a1e1-cd4154162a78) | Admin login page             |
| 25 | ![](https://github.com/user-attachments/assets/a9b178cf-94c7-4e11-b173-717e05dc820a) | Upcoming reservations widget |

---

## 📄 License

Distributed under the [MIT License](LICENSE).

---

*Update DB creds, paths, and screenshots before pushing to production.*
