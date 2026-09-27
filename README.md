# Beforbim — Premium Engineering Learning Management System

> **Beforbim** is a high-performance, modular Learning Management System (LMS) specifically engineered for Building Information Modeling (BIM) education, computational design, and architectural/civil engineering certification.

---

## 🏗️ Architecture & Technology Stack

- **Framework:** Laravel 13 (Modular Monolith Architecture)
- **Backend:** PHP 8.3+ with strict type hinting
- **Database:** MySQL 8.4 (52 relational tables with foreign key constraints and audit logging)
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Vite
- **Typography:** Tajawal & IBM Plex Sans Arabic (Arabic RTL), Inter (English), JetBrains Mono (Technical/Data)
- **Security:** Single concurrent device enforcement (`DeviceSession`), fine-grained RBAC, and academic audit logging (`AuditLog`).

---

## 🎨 Visual Identity & Brand Design Tokens

The visual language communicates engineering precision, accredited professional certification, and premium quality:

| Color Token | Hex Code | Primary Usage |
| :--- | :--- | :--- |
| **Deep Navy** | `#071A36` | Primary headers, navbars, premium cards, dark surfaces |
| **Royal Engineering Blue** | `#123B68` | Secondary surfaces, section cards, engineering accents |
| **Gold Accent** | `#D4AF37` | CTAs, active highlights, badges, progress indicators |
| **Light Gold** | `#F3D98B` | Subtle hover states, glow borders, accent text |
| **Clean White** | `#FFFFFF` | Core reading surfaces and high-contrast typography |
| **Soft Gray** | `#F5F7FA` | Background sections and secondary table headers |

---

## 🚀 Getting Started (Local Development)

### 1. Prerequisites
- **Laragon** (or PHP 8.3+ and MySQL 8.4+)
- **Composer 2.7+**
- **Node.js 20+ & npm**

### 2. Environment Configuration
```bash
# Clone the repository and navigate to root
cd C:\laragon\www\beforbim

# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

Ensure your `.env` contains your local database credentials:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beforbim_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Dependencies & Migrations
```bash
# Install PHP dependencies
composer install

# Run database migrations and seed baseline data
php artisan migrate --seed

# Create storage symlink
php artisan storage:link
```

### 4. Running the Development Server
```bash
# Run Laravel development server (if not using Laragon virtual host http://beforbim.test)
php artisan serve

# In a separate terminal, compile frontend assets with Vite
npm install
npm run dev
```

---

## 🔑 Default Seeded Accounts

For testing and local evaluation (Default password: `password123`):

| Role | Email | Access Scope |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@beforbim.com` | Full enterprise control, system settings, financial audit |
| **Operations Admin** | `admin@beforbim.com` | Course approval, user account moderation, support tickets |
| **BIM Instructor** | `instructor@beforbim.com` | Curriculum builder, course authoring, project reviews |
| **Engineering Student** | `student@beforbim.com` | Learning studio, enrolled courses, assessments |

---

## 🧪 Testing

Execute the comprehensive feature and unit test suites:

```bash
# Run all PHPUnit feature tests
php artisan test

# Run authentication and security access tests
php artisan test tests/Feature/AuthenticationAndAccessTest.php
```

---

## 📁 Modular Directory Overview

```
app/Modules/
├── AccessControl/      # Roles & Permissions (RBAC)
├── Assessment/         # Quizzes, questions, attempts, and auto-grading
├── Assignment/         # Engineering project submissions and reviews
├── AuditLog/           # Academic and security traceability
├── Auth/               # Authentication, device session control, dashboards
├── Course/             # Course CRUD, categories, requirements, and learning paths
├── Curriculum/         # Course sections, reordering, and structure
├── DeviceSession/      # Concurrent device limitation engine
├── Enrollment/         # Course enrollment lifecycle and access control
├── Lesson/             # Video lessons, text content, and materials
├── Media/              # Centralized polymorphic media management
├── Notification/       # Domain event architecture and alerts
├── Order/ & Payment/   # Cart, checkout, bank transfers, and invoicing
└── User/               # User account lifecycle and instructor profiles
```

---

## 📜 Documentation

- [Deployment Guide](file:///c:/laragon/www/beforbim/docs/DEPLOYMENT.md)
- [Database Schema & ERD](file:///c:/laragon/www/beforbim/docs/DATABASE.md)
- [Module Architecture](file:///c:/laragon/www/beforbim/docs/MODULES.md)
- [User Workflows](file:///c:/laragon/www/beforbim/docs/USER_FLOWS.md)
