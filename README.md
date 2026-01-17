# Incident Tracker (POC)

A small Laravel 9 app to manage incidents per service (SRE-style), with updates, attachments, subscriptions, async notifications, and scheduled digests.

This POC is designed to be cloud-friendly (managed DB, cache, object storage, queues, scheduler, logs).

## Features

### MVP
- Authentication (starter kit)
- Services CRUD
- Incidents CRUD (severity, status, filters)
- Incident updates (timeline)
- Attachments per update (prepared for S3-compatible storage)

### Pro (Cloud-ready)
- Subscriptions (watch a service or incident)
- Async notifications via queues (jobs/events/listeners)
- Daily scheduled digest / stale incident reminders

### Optional
- Realtime dashboard (broadcasting/websockets)
- Basic metrics (MTTA/MTTR)

## Tech Stack
- **Framework:** Laravel 9
- **PHP:** 8.2+ (tested with 8.2.30)
- **Frontend:** Blade + Tailwind CSS
- **Node:** 22.x LTS (tested with 22.22.0)
- **Database:** PostgreSQL or MySQL (SQLite for local dev)
- **Cache/Queue:** Redis-compatible (Valkey/Redis)
- **Object Storage:** S3-compatible (Cloudflare R2 recommended)

## Local Setup

### Requirements
- PHP 8.2+ with extensions: mbstring, xml, bcmath, pdo, pdo_mysql/pdo_pgsql
- Composer 2.x (tested with 2.9.3)
- Node.js 22.x + npm 10.x
- A local database (SQLite/PostgreSQL/MySQL)
- Redis (optional for local, required for production queues)

> **📋 Need to install or verify these tools?** See [PREWORK.md](PREWORK.md) for detailed macOS setup instructions.

### Install
```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev
```

## Authentication Scaffolding (Laravel Breeze)

This project uses **Laravel Breeze (Blade + Tailwind + Vite)** for the default authentication UI and routes (login, register, password reset).

### Why Breeze
- Fastest starter kit for a Laravel 9 POC
- Uses Blade views (server-rendered) and keeps the stack simple
- Includes Tailwind + Vite dev workflow out of the box

### Install Breeze (first time only)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade

npm install
npm run dev

php artisan migrate
```

---

## Roles & Authorization

This app implements three user roles with different permissions:

| Role | Permissions | Description |
|------|-------------|-------------|
| **Admin** | Full access | Can manage everything, bypasses all policies |
| **Responder** | Create/Update | Can create incidents, add updates, change status, upload attachments |
| **Viewer** | Read-only | Can view incidents and services but cannot modify |

### Implementation Details

- **Role Enum:** [`app/Enums/Role.php`](app/Enums/Role.php) - PHP 8.1+ backed enum
- **User Model:** [`app/Models/User.php`](app/Models/User.php) - Casts `role` column to `Role` enum
- **Authorization:** [`app/Providers/AuthServiceProvider.php`](app/Providers/AuthServiceProvider.php) - `Gate::before()` grants admins full access
- **Database:** `users.role` column (string, default: `viewer`)

### Setup Roles (first time only)

```bash
# Create and run migration to add role column
php artisan make:migration add_role_to_users_table --table=users
php artisan migrate

# Seed test users with different roles
php artisan make:seeder UserSeeder
php artisan db:seed --class=UserSeeder
```

### Test Users (after seeding)

| Email | Password | Role |
|-------|----------|------|
| `admin@example.com` | `password` | Admin |
| `responder@example.com` | `password` | Responder |
| `viewer@example.com` | `password` | Viewer |

### Usage in Code

```php
// Check roles
if (auth()->user()->isAdmin()) { /* ... */ }
if (auth()->user()->canWrite()) { /* admin or responder */ }

// In controllers (throws 403 if unauthorized)
$this->authorize('create', Incident::class);

// In Blade templates
@can('update', $incident)
    <button>Edit Incident</button>
@endcan
```

---

## Running the Application

### Development Servers

Run these in **separate terminals**:

**Terminal 1 - Vite (frontend hot-reload):**
```bash
npm run dev
# Runs on http://localhost:5173
# Auto-refreshes when you edit .blade.php, .js, .css
```

**Terminal 2 - Laravel (backend):**
```bash
php artisan serve
# Runs on http://localhost:8000
# Visit this URL in your browser
```

**Terminal 3 - Queue Worker (when using jobs):**
```bash
php artisan queue:work
# Processes background jobs for notifications
```

### Verify Installation

Visit **http://localhost:8000** - you should see the Laravel welcome page with login/register links.

---

## Development Workflow

### Database

```bash
# Create a new migration
php artisan make:migration create_services_table

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration + seed
php artisan migrate:fresh --seed
```

### Testing with Tinker

```bash
# Quick check
php artisan tinker --execute="dump(App\Models\User::count());"

# Interactive mode
php artisan tinker
>>> $admin = User::where('email', 'admin@example.com')->first();
>>> $admin->role->value
=> "admin"
```

### Logs

```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log
```

---

## Project Structure

```
app/
├── Enums/
│   └── Role.php                 # User roles enum
├── Models/
│   └── User.php                 # User model with role casting
├── Providers/
│   └── AuthServiceProvider.php  # Gate::before admin override
└── Policies/                    # Authorization policies (to be created)

database/
├── migrations/
│   └── *_add_role_to_users_table.php
└── seeders/
    └── UserSeeder.php           # Test users with roles

resources/
├── views/                       # Blade templates (from Breeze)
├── js/
└── css/
```

---

## Next Steps

- [ ] Create core models (Service, Incident, IncidentUpdate, Attachment, Subscription)
- [ ] Create Policies for authorization
- [ ] Build CRUD controllers + views
- [ ] Add events/listeners/jobs for notifications
- [ ] Configure queues and scheduler

---

## Troubleshooting

### Permission errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Vite not connecting
Make sure `npm run dev` is running in a separate terminal.