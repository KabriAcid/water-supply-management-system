# 💧 Water Supply Management System

A lightweight water tank delivery request platform built using **raw PHP (PDO)**. Users can register, log in, and place water orders. Admin manually manages orders via a protected backend. **No emails, stats, or external packages. Cash on Delivery only.**

---

## 📁 Directory Structure

/water-supply-system/
│
├── config/
│   └── db.php                // PDO database connection
│
├── auth/
│   ├── login.php             // Login logic for users/admins
│   ├── register.php          // User registration
│   └── logout.php
│
├── users/
│   ├── dashboard.php         // Place order + order history
│   └── place_order.php       // Form handler
│
├── admin/
│   ├── login.php
│   ├── dashboard.php         // View + update order status
│   └── update_order.php
│
├── index.php
└── .htaccess (optional clean URLs)


---

## 🧾 Features

### 👤 User Features
- Register and log in
- Place water delivery orders with:
  - Quantity
  - Delivery address
  - (Optional) Preferred delivery date
- View past orders and track their status:
  - `Pending`, `In Progress`, `Delivered`

### 👨‍💼 Admin Features
- Login with hardcoded DB credentials
- View all orders from all users
- See user contact details with each order
- Update order status via dropdown or button
- Status workflow:  
  `Pending → In Progress → Delivered`

---

## 🗃️ Database Schema

### `users` Table
| Column         | Type            |
|----------------|-----------------|
| id             | INT, PK, AI     |
| name           | VARCHAR(100)    |
| email          | VARCHAR(100)    |
| phone          | VARCHAR(15)     |
| password       | VARCHAR(255)    |
| created_at     | TIMESTAMP       |

### `admins` Table
| Column         | Type            |
|----------------|-----------------|
| id             | INT, PK, AI     |
| username       | VARCHAR(100)    |
| password       | VARCHAR(255)    |

> 🔐 Insert admin manually with a hashed password.

### `orders` Table
| Column             | Type                      |
|--------------------|---------------------------|
| id                 | INT, PK, AI               |
| user_id            | INT (FK → users.id)       |
| quantity           | INT                       |
| delivery_address   | TEXT                      |
| delivery_date      | DATE (nullable)           |
| status             | ENUM (Pending, In Progress, Delivered) |
| created_at         | TIMESTAMP                 |

---

## 🔐 Authentication & Sessions

- `$_SESSION` is used for login sessions.
- Session started with `session_start()` on all secure pages.
- Users and admins are handled separately.
- Protected pages verify session existence (`user_id` or `admin_id`).

---

## ⚙️ Project Setup Guide

### 1. Create Database
```sql
CREATE DATABASE water_db;
USE water_db;
-- Create users, admins, and orders tables (see schema above)
