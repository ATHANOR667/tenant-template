# ATHANOR667/tenant-template

## Overview

### Summary

This repository is a modular Laravel 12 application that serves as a robust template for building systems with advanced administrative backends. It features a multi-tiered user structure with 'Admins' and a 'Super-Admin', complete with role-based permissions, comprehensive activity logging, and a user banning system.

### Purpose

The project aims to provide a foundational boilerplate for complex web applications, eliminating the need to build common but sophisticated features like modular architecture, multi-level authentication, and detailed auditing from scratch. It is particularly well-suited for projects requiring a secure and feature-rich administration panel.

### Key Features

• Modular architecture using `nwidart/laravel-modules`.
• Multi-tiered authentication with distinct 'Admin' and 'Super-Admin' guards.
• Comprehensive role and permission management via `spatie/laravel-permission`.
• Detailed user connection and model activity logging.
• Built-in user banning system with configurable levels and durations.
• Dynamic UI powered by Livewire and styled with Tailwind CSS.
• Background job processing with Laravel Horizon and application monitoring with Laravel Pulse.

## Technical Stack

### Languages

PHP, JavaScript, CSS (Sass)

### Frameworks

Laravel 12, Livewire 3, Tailwind CSS 4

### Key Dependencies

nwidart/laravel-modules, livewire/livewire, spatie/laravel-permission, laravel/horizon, laravel/pulse, barryvdh/laravel-dompdf, twilio/sdk, stevebauman/location

### Tools

Vite, Composer, PHPUnit, Laravel Pint, Laravel Sail (Docker)

## Project Structure

### Architecture

The application is built on a modular architecture using the `nwidart/laravel-modules` package. The core administrative functionality is encapsulated within the `AdminBase` module, promoting code organization, separation of concerns, and reusability. Within both the root application and the module, it follows the standard Model-View-Controller (MVC) pattern.

### Main Components

**AdminBase Module** `Modules/AdminBase/`

The central module containing all logic for the administration panel, including authentication for Admins and Super-Admins, user management, roles, permissions, and extensive logging features.

**Multi-Guard Authentication** `config/auth.php, Modules/AdminBase/app/Models/, Modules/AdminBase/app/Http/Middleware/`

Implements separate authentication systems for 'Admin' and 'Super-Admin' users, each with its own model, middleware, and routes, allowing for distinct access levels and privileges.

**Logging & Auditing System** `Modules/AdminBase/app/Services/, Modules/AdminBase/app/Models/, Modules/AdminBase/app/Livewire/Logs/`

A suite of services, models, and Livewire components for tracking user connections (IP, location, device), and recording all model creations, updates, and deletions for auditing purposes.

**Role & Permission Management** `Modules/AdminBase/database/seeders/RoleSeeder.php, Modules/AdminBase/resources/views/super-admin/pages/manage-admins.blade.php`

Utilizes the `spatie/laravel-permission` package, with Livewire components providing a user interface for the Super-Admin to manage roles and assign permissions to Admins.

**Background Job Processing** `app/Jobs/UnbanExpiredBansJob.php, app/Providers/HorizonServiceProvider.php`

Uses Laravel's queue system, monitored by Horizon, to handle background tasks such as automatically lifting expired user bans.

### Directory Structure

The project follows a standard Laravel directory structure, which is extended by a top-level `Modules/` directory. The `AdminBase` module within it mirrors a typical Laravel application, containing its own `app`, `config`, `database`, `resources`, and `routes` subdirectories, effectively creating a self-contained application within the main project.

## Setup Instructions

### Prerequisites

• PHP ^8.2
• Composer
• Node.js & npm
• A supported database (e.g., MySQL, PostgreSQL, SQLite)
• Docker (recommended for Laravel Sail)

### Installation Steps

1. Clone the repository: `git clone <repository-url>`
2. Navigate into the project directory: `cd <project-directory>`
3. Install PHP dependencies: `composer install`
4. Install JavaScript dependencies: `npm install`
5. Create an environment file: `cp .env.example .env`
6. Generate an application key: `php artisan key:generate`
7. Configure your database credentials in the `.env` file.
8. Run database migrations and seeders: `php artisan migrate --seed`
9. Build front-end assets: `npm run build`

### Configuration

The main application configuration is managed through the `.env` file. You must set up the `DB_*` variables for database connectivity and `APP_URL`. For features like email (OTP) and SMS notifications, the `MAIL_*` and `TWILIO_*` variables must also be configured.

### Running Locally

The project includes a convenient script for local development. Run `composer run dev`. This command uses `concurrently` to start the PHP development server, a queue worker, the Pail log watcher, and the Vite front-end server simultaneously.

## Configuration

### Environment Variables

**`APP_URL`** (Required)

The base URL of the application.

**`DB_CONNECTION`** (Required)

The database driver to use (e.g., mysql, pgsql, sqlite).

**`DB_DATABASE`** (Required)

The name of the database.

**`QUEUE_CONNECTION`** (Required)

The driver for the queue system (defaults to 'database').

**`MAIL_MAILER`**

The driver for sending emails (e.g., smtp, log). Required for OTP functionality.

**`TWILIO_SID`**

Twilio Service ID for SMS/WhatsApp notifications.

**`TWILIO_TOKEN`**

Twilio authentication token.

### Configuration Files

• `config/auth.php`: Defines the `admin` and `super-admin` authentication guards and their corresponding Eloquent providers.
• `config/filesystems.php`: Configures storage disks for public and private files.
• `config/pulse.php` & `config/horizon.php`: Configuration for Laravel's monitoring and queue management dashboards.
• `modules_statuses.json`: A file used by `nwidart/laravel-modules` to enable or disable modules.

## Development

### Code Style

The codebase adheres to Laravel's standard conventions and PSR standards. The inclusion of `laravel/pint` in development dependencies suggests that an automated code style fixer is used to maintain consistency. Business logic is well-encapsulated in Service classes, and reusable model logic is abstracted into Traits.

### Testing

The project is set up with PHPUnit for testing. Basic feature and unit test examples are present, but no application-specific tests were found in the provided files. The structure is in place to add comprehensive tests.

### Contributing

To contribute, new features should be developed within the existing modular structure. Administrative features should be added to the `AdminBase` module. All code should follow the established style, and new functionality should ideally be accompanied by unit or feature tests.

## Additional Notes

The project is named 'tenant-template' in the README, which strongly implies its intended use as a foundation for multi-tenant applications. While no explicit multi-tenancy package is installed, the modular and multi-user architecture provides a solid starting point for such a system. The `Admin` model includes fields for identity verification (`pieceIdentiteRecto`, `pieceIdentiteVerso`), indicating it's designed for a formal, high-security environment.


