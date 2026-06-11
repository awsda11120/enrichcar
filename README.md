# Enrichcar — Vehicle Insurance & Tax Renewal Management System

A web application for managing vehicle insurance (พ.ร.บ.) and annual tax renewal services, built as a senior project at Thammasat University.

> **User Satisfaction Score: 4.6 / 5.0** (evaluated by 10 real users)  
> **Academic Report:** [Final Project Report (PDF)](Final%20Project_Awasada%20Oxzu.pdf)

---

## Overview

Enrichcar is designed for small vehicle service shops and agencies that handle compulsory car insurance (พ.ร.บ.) and annual vehicle tax renewals on behalf of customers. Staff can track customers, vehicles, documents, costs, and delivery status — all in one place.

**Built by:** Wannida Phanrak & Awasada Oxzu  
**Institution:** Faculty of Science and Technology, Thammasat University  
**Academic Year:** 2024

---

## Features

- **Customer & Vehicle Management** — Add, edit, search customers and their registered vehicles
- **Insurance Cost Calculation** — Auto-calculate compulsory insurance (พ.ร.บ.) fees based on vehicle type
- **Vehicle Tax Calculation** — Complex tax logic based on engine CC, vehicle weight, and age-based discounts (up to 50%)
- **Document Tracking** — Monitor status of insurance and tax documents per customer
- **Delivery Management** — Track document pickup and delivery status
- **Dashboard & Analytics** — Visual charts (monthly revenue, document counts, status breakdown) powered by Chart.js
- **Pricing Settings** — Configurable service fees and cost parameters
- **Province Support** — All 77 Thai provinces pre-loaded via database seeder

---

## Screenshots

> Screenshots of all major pages are available in the [project report (PDF)](Final%20Project_Awasada%20Oxzu.pdf), pages 53–63.

| Page | Description |
|------|-------------|
| Dashboard | Overview charts and monthly summary |
| Customer List | Search and manage customer records |
| Vehicle Info | View and edit vehicle details |
| Cost Calculation | Auto-calculate insurance + tax costs |
| Document Tracking | Status tracking per customer |
| Delivery Status | Manage pickup/delivery |
| Settings | Configure pricing and service fees |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.2, Laravel 11 |
| Frontend | Blade Templates, Bootstrap 5.3, jQuery |
| Charts | Chart.js |
| Database | SQLite (default) / MariaDB |
| Architecture | MVC |

---

## Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/enrichcar.git
cd enrichcar

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create SQLite database
touch database/database.sqlite

# 6. Run migrations and seed initial data
php artisan migrate --seed

# 7. Start the development server
php artisan serve
```

Then open [http://localhost:8000](http://localhost:8000) in your browser.

### Database

The app uses **SQLite by default** — no database server setup required.

To use MariaDB/MySQL instead, update these values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=enrichcar
DB_USERNAME=root
DB_PASSWORD=
```

---

## Database Schema

| Table | Description |
|-------|-------------|
| `customers` | Customer personal information |
| `cars` | Vehicle records linked to customers |
| `histories` | Insurance/tax transaction records |
| `taxes` | Vehicle tax rate table (CC/weight-based) |
| `provinces` | All 77 Thai provinces |
| `settings` | Configurable pricing values |
| `settings_renew` | Renewal-specific cost settings |

---

## Project Structure

```
app/
├── Http/Controllers/
│   ├── AdminController.php      # Customer & vehicle CRUD
│   ├── CalculateController.php  # Insurance & tax calculation logic
│   ├── ChartController.php      # Dashboard data & analytics
│   └── SettingsController.php   # Pricing configuration
├── Models/
resources/views/                 # Blade templates (17 pages)
database/
├── migrations/
└── seeders/                     # Province & tax rate seed data
```

---

## Known Limitations

- No user authentication — designed for internal/trusted use on a local network
- Single-shop use only; no multi-tenant support
- Not deployed to a public server (localhost only)

---

## Documentation

The full academic report includes system design diagrams (Context Diagram, DFD, Activity Diagrams, ER Diagram), implementation details, and user evaluation results.

[Download Full Report (PDF)](Final%20Project_Awasada%20Oxzu.pdf)
