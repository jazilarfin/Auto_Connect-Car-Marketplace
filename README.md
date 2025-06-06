# Auto_Connect: A Car Marketplace Platform

Welcome to **Auto_Connect**, your ultimate destination for buying and selling cars online. This project was developed as part of the **Database Systems Lab** at the **National University of Computer and Emerging Sciences (FAST-NU), Lahore**, during the **Spring 2024** semester.

---

## 🚗 Overview

**Auto_Connect** is a full-featured car marketplace designed to provide users with a seamless experience for browsing, listing, and managing automobile transactions. It supports multiple user roles, secure authentication, and admin controls, offering a robust platform for vehicle trading.

---

## 👥 Team Members




## ✨ Key Features

### 🔐 User Authentication & Management
- User registration and login system
- Role-based access (buyer, seller, admin)
- Profile editing: contact info, password, etc.

### 🚘 Car Catalog
- List cars with filtering by city, price, and other attributes
- Quick browsing and intelligent search functionality

### 📋 Vehicle Listings
- Sellers can create, edit, and manage listings
- Each listing includes:
  - Make, model, year, mileage
  - Price, city, and description

### 🛠 Admin Panel
- Admin dashboard to manage users and car listings
- Content moderation tools
- View reported content and enforce policies

---

## 🛠 Technologies Used

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL (via phpMyAdmin in XAMPP)
- **Web Server:** Apache (XAMPP)

---

## 🚀 How to Run Locally

1. Install [XAMPP](https://www.apachefriends.org).
2. Place the `Auto_Connect` project folder in `C:\xampp\htdocs\`.
3. Start **Apache** and **MySQL** from the XAMPP Control Panel.
4. Open `http://localhost/phpmyadmin` and import the provided `.sql` file.
5. Go to `http://localhost/Auto_Connect/` in your browser to run the app.

---

## 📁 Folder Structure

Auto_Connect/
├── index.php
├── login.php
├── register.php
├── admin/
│ └── dashboard.php
├── seller/
│ └── manage_listings.php
├── buyer/
│ └── browse_cars.php
├── assets/
│ └── css, js, images
└── database/
└── db_connection.php

