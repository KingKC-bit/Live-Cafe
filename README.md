# Live Cafe E-Commerce & Running Club Platform

A database-driven web application developed for **Live Cafe**, a cafe and running club based in Sandton, South Africa.

The system combines an online ordering platform, running club management, administration tools, and a point-of-sale (POS) system into one centralised application.

---

## Table of Contents

* [Project Overview](#project-overview)
* [Business Problem](#business-problem)
* [Project Goals](#project-goals)
* [Main Components](#main-components)
* [Features](#features)
* [Technology Stack](#technology-stack)
* [System Architecture](#system-architecture)
* [Database](#database)
* [Project Structure](#project-structure)
* [Installation](#installation)
* [Running the Application](#running-the-application)
* [Database Setup](#database-setup)
* [Authentication and Authorisation](#authentication-and-authorisation)
* [Git Workflow](#git-workflow)
* [Development Status](#development-status)
* [Future Improvements](#future-improvements)
* [Team](#team)

---

## Project Overview

Live Cafe currently operates as both a cafe and a running club.

Customers interact with the business through several disconnected channels, including:

* Walk-in purchases at the cafe
* WhatsApp and social media messages
* Saturday morning running events

The purpose of this project is to develop a centralised web application that brings these activities together.

The platform will allow customers to browse products, place collection orders, view running club events and RSVP. Staff will be able to record physical sales through the POS system, while administrators will be able to manage products, inventory, orders, events, users and other business operations.

The application is being developed using **Laravel, PHP and MySQL**, with Docker/Laravel Sail being used for the development environment.

---

## Business Problem

Live Cafe currently experiences several operational challenges.

### Disconnected ordering channels

Customers can contact the business through WhatsApp, social media or in person. This can make orders and customer communication difficult to manage.

### Faulty or delayed POS

The existing POS system can experience lag or fail to capture some physical sales.

### Stock management

Stock is manually monitored and products can become unavailable when stock runs out. Online and physical sales therefore need to work from the same inventory.

### Running club planning

Running events can be difficult to plan because the business does not always know how many people will attend in advance.

### Food preparation

Some food items require preparation time. Allowing customers to order in advance gives the cafe an opportunity to prepare collection orders before customers arrive.

---

## Project Goals

The system aims to:

1. Provide an online ordering system for Live Cafe.
2. Allow customers to order food and drinks for collection.
3. Support future merchandise sales.
4. Centralise online and physical sales records.
5. Maintain shared inventory between the online shop and POS.
6. Allow customers to view running club events.
7. Allow registered users to RSVP to running events.
8. Give administrators a central dashboard.
9. Improve product and inventory management.
10. Provide better records of orders, sales and customer activity.

---

# Main Components

The system is divided into four major components.

## 1. E-Commerce / Shop

Customers can:

* Browse available products
* Filter products by category
* View product information
* Add products to a cart
* Place collection orders
* View their orders
* Track order status

Products can include:

* Food
* Drinks
* Merchandise
* Add-ons

The system is designed for **collection only**. Delivery is not currently part of the project scope.

---

## 2. Running Club

The running club component allows users to:

* View upcoming running events
* View event information
* RSVP to events
* Specify additional attendees
* View announcements

RSVP registration is intended to close **no later than 8 hours before the event** so that Live Cafe has time to prepare.

---

## 3. Administration

Administrators will have access to an administration dashboard.

The dashboard will provide information about:

* Users
* Products
* Available stock
* Orders
* Sales
* Running events
* RSVPs

Administrators will also be able to manage relevant business data.

---

## 4. Point of Sale (POS)

The POS system is designed for staff and administrators to record physical cafe sales.

It will support:

* Product selection
* Quantities
* Order totals
* Cash payments
* Card payments
* Stock updates
* Sales records

The system is also planned to support temporary offline operation, allowing sales to be recorded when the connection is unavailable and synchronised when connectivity returns.

---

# Features

## Customer Features

* Registration and login
* Product browsing
* Product categories
* Product details
* Shopping cart
* Collection checkout
* Order history
* Order status
* Running club events
* Running club RSVP
* Event announcements

## Staff Features

* POS access
* Physical sales
* Cash/card payment recording
* Inventory updates

## Administrator Features

* Admin dashboard
* User management
* Product management
* Inventory management
* Order management
* Sales management
* Event management
* Announcement management
* Partnership management
* POS access

---

# Technology Stack

## Backend

* PHP
* Laravel
* Laravel MVC
* Laravel Breeze
* Laravel Policies
* Laravel Form Requests
* Laravel Services/Actions
* Laravel Model Observers
* Laravel Queues

## Frontend

* Blade
* HTML
* CSS
* JavaScript
* Bootstrap where appropriate
* Laravel Livewire for the POS interface

## Database

* MySQL 8.0+
* InnoDB

## Development Environment

* Docker
* Laravel Sail
* phpMyAdmin
* Git
* GitHub

## Planned Infrastructure

* DigitalOcean
* Ubuntu 24.04 LTS
* Redis
* Laravel Reverb
* GitHub Actions
* CI/CD

---

# System Architecture

The application follows the Laravel MVC architecture.

```text
Browser
   │
   ▼
Routes
   │
   ▼
Controllers
   │
   ├── Form Requests
   │
   ├── Policies
   │
   └── Services / Actions
   │
   ▼
Models / Eloquent ORM
   │
   ▼
MySQL Database
```

The project uses a separation of responsibilities:

```text
Middleware
    ↓
Controls access to areas of the application

Policies
    ↓
Controls permission to perform actions on resources

Controllers
    ↓
Handle HTTP requests and responses

Services / Actions
    ↓
Handle business rules

Models
    ↓
Handle data and relationships

Blade / Livewire
    ↓
User interface
```

Controllers are intended to remain relatively thin, with complex business logic handled by services or actions.

---

# Database

The current database contains the following main tables:

```text
users
categories
products
ingredients
product_ingredients
orders
order_details
sales
sales_details
announcements
events
rsvps
partnerships
partnership_members
images
```

### Important relationships

```text
User
 ├── Orders
 ├── Sales
 ├── RSVPs
 └── Partnership Members

Category
 └── Products

Product
 ├── Category
 ├── Ingredients
 ├── Order Details
 ├── Sales Details
 └── Images

Order
 └── Order Details

Sale
 └── Sales Details

Event
 └── RSVPs

Partnership
 └── Partnership Members
```

Product images use a polymorphic `images` table so that the image system can be extended to other models in the future.

---

# User Roles

The system currently uses three main user roles:

| Role     | Access                             |
| -------- | ---------------------------------- |
| Customer | Shop, orders, events and RSVPs     |
| Staff    | POS and operational sales          |
| Admin    | Administration, POS and management |

Role-based middleware controls access to application areas.

For example:

```php
Route::middleware(['auth', 'role:staff,admin'])
    ->group(function () {
        // POS routes
    });
```

Administrators use:

```php
Route::middleware(['auth', 'role:admin'])
    ->group(function () {
        // Admin routes
    });
```

Policies are then used for more specific resource-level authorisation.

---

# Project Structure

The application uses one Laravel project.

The main application structure is organised by feature.

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── POS/
│   │   ├── Running/
│   │   └── Shop/
│   │
│   ├── Middleware/
│   └── Requests/
│
├── Models/
│
├── Policies/
│
└── Services/
```

Blade views follow the same feature structure:

```text
resources/
└── views/
    ├── layouts/
    ├── components/
    ├── home.blade.php
    │
    ├── shop/
    │   ├── index.blade.php
    │   ├── show.blade.php
    │   ├── cart.blade.php
    │   └── checkout.blade.php
    │
    ├── running/
    │   ├── index.blade.php
    │   └── show.blade.php
    │
    ├── admin/
    │   ├── dashboard.blade.php
    │   ├── products/
    │   ├── orders/
    │   ├── events/
    │   └── ...
    │
    └── pos/
        └── index.blade.php
```

---

# Installation

## Requirements

Make sure the following are installed:

* Git
* Docker Desktop
* PHP
* Composer
* Node.js and npm

Laravel Sail handles the application's Docker services.

---

## Clone the Repository

```bash
git clone <repository-url>
cd <project-folder>
```

---

## Install PHP Dependencies

```bash
composer install
```

---

## Install Frontend Dependencies

```bash
npm install
```

---

## Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
./vendor/bin/sail artisan key:generate
```

Configure the database values in `.env` according to the Docker/Sail setup.

---

# Running the Application

Start the Laravel Sail containers:

```bash
./vendor/bin/sail up -d
```

Check the running containers:

```bash
./vendor/bin/sail ps
```

The application can normally be accessed through:

```text
http://localhost
```

---

## Running Vite

For frontend development:

```bash
./vendor/bin/sail npm run dev
```

Keep this process running while developing frontend assets.

---

# Database Setup

Run the migrations:

```bash
./vendor/bin/sail artisan migrate
```

To completely rebuild the database and seed the development data:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

The seeders create development data for:

* Users
* Categories
* Products
* Ingredients
* Product images
* Events
* RSVPs
* Announcements
* Partnerships
* Partnership members
* Orders
* POS sales

---

# Clearing Laravel Cache

If routes, configuration or views are behaving unexpectedly, run:

```bash
./vendor/bin/sail artisan optimize:clear
```

Then refresh the browser.

---

# Git Workflow

The project uses GitHub for collaborative development.

The main branch contains the stable foundation of the project.

Feature development should be performed on separate branches.

Example:

```bash
git checkout -b feature/shop
```

After completing work:

```bash
git add .
git commit -m "Add shop product listing"
git push origin feature/shop
```

Changes should then be reviewed and merged into the main branch through a Pull Request.

---

# Development Status

## Completed

* Project planning
* Database design
* EERD and system design
* Responsive prototypes
* Laravel project setup
* Docker / Laravel Sail
* GitHub repository
* Database migrations
* Eloquent models
* Model relationships
* Factories
* Seeders
* Development database data
* Authentication
* Role middleware
* Initial policies
* Initial controllers

## Currently In Development

* Route structure
* Blade view structure
* Shared application layout
* Shop pages
* Running club pages
* Admin pages
* POS interface

## Planned

* Shopping cart
* Checkout
* Order processing
* Inventory management
* Stock validation
* POS transactions
* Offline POS synchronisation
* Running club RSVP rules
* Admin management pages
* Laravel Reverb real-time updates
* Queues and Redis
* Model observers
* Automated testing
* GitHub Actions CI/CD
* DigitalOcean deployment

---

# Future Improvements

Potential future improvements include:

* Online payment integration such as PayFast if required by the client
* Product variations
* Product add-ons
* More advanced inventory tracking
* Supplier management
* Wastage tracking
* Collection time management
* Customer notifications
* Email notifications
* Improved reporting and analytics
* Expanded running club functionality
* Improved offline POS synchronisation
* Production monitoring

---

# Team

This project is being developed as a group project consisting of four students.

The project follows a shared Laravel architecture where the database, models, authentication, core routing and other foundational components are maintained centrally.

Feature development is divided between the team to reduce merge conflicts and allow members to work independently.

---

# Project Purpose

The final system is intended to provide Live Cafe with a central platform for managing its cafe operations, online collection orders, physical sales and running club activities.

The project focuses on improving the connection between customers, sales, inventory and running club events while providing a foundation that can be expanded as the business grows.
