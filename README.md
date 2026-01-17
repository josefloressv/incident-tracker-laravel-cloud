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

## Laravel Diagnostics & Inspection (Read-Only Commands)

These commands **inspect** your Laravel app without modifying code or data. Safe to run anytime.

### System & Environment

```bash
# Laravel version
php artisan --version

# System overview (Laravel, PHP, drivers, cache, queue)
php artisan about

# Show current environment (local/production/etc)
php artisan env

# Verify PHP extensions
php -m | grep -E "pdo|sqlite|mysql|redis"

# Show composer packages with versions
composer show
composer show --tree  # with dependencies
```

### Routes & Application Structure

```bash
# List all registered routes
php artisan route:list

# Filter routes by name/path/method
php artisan route:list --path=api
php artisan route:list --method=POST
php artisan route:list --name=incidents

# List available Artisan commands
php artisan list

# Show command help
php artisan help migrate
```

### Database & Models

```bash
# Check migration status
php artisan migrate:status

# Show model details (columns, relations, observers)
php artisan model:show User
php artisan model:show App\\Models\\Incident

# Quick database check via tinker
php artisan tinker --execute="dump(App\Models\User::count());"
```

### Events, Jobs & Scheduling

```bash
# List registered events and listeners
php artisan event:list

# Show scheduled tasks (cron)
php artisan schedule:list

# List queued jobs (requires queue driver configured)
php artisan queue:failed
```

### Configuration & Cache

```bash
# Inspect effective config values
php artisan config:show database
php artisan config:show cache
php artisan config:show queue

# View all compiled config
php artisan config:show
```

### Useful Combinations

```bash
# Full system check
php artisan about && php artisan migrate:status

# Verify routes and models exist
php artisan route:list --path=services && php artisan model:show Service

# Check event wiring
php artisan event:list | grep Incident
```

## Database Schema & Models

📊 **[View full database schema diagram](docs/database-schema.md)**

### Core Models

This app uses 5 core models to track services and incidents:

| Model | Purpose | Key Relationships |
|-------|---------|------------------|
| **Service** | Systems/services being monitored | Has many incidents, polymorphic subscriptions |
| **Incident** | Active issues/outages | Belongs to service, has updates and attachments |
| **IncidentUpdate** | Timeline entries for incidents | Belongs to incident, can have attachments |
| **Attachment** | File uploads (S3-compatible) | Polymorphic: belongs to incident or update |
| **Subscription** | User notifications | Polymorphic: subscribe to service or incident |

### Creating Models & Migrations

```bash
# Generate model + migration in one command
php artisan make:model Service -m
php artisan make:model Incident -m
php artisan make:model IncidentUpdate -m
php artisan make:model Attachment -m
php artisan make:model Subscription -m
```

### Migration Schema Details

**Services Table:**
- `name`, `slug` (unique), `description`, `status` (active/maintenance/deprecated)
- `owner_id` → users (nullable)
- Indexed: `status`

**Incidents Table:**
- `title`, `description`, `status` (open/investigating/mitigating/monitoring/resolved)
- `severity` (p1/p2/p3/p4), `resolved_at` (timestamp)
- `service_id` → services, `created_by` → users
- Indexed: `status`, `severity`, `created_at`

**Incident Updates Table:**
- `message`, `status` (nullable, for status changes)
- `incident_id` → incidents, `created_by` → users
- Indexed: `incident_id`, `created_at`

**Attachments Table (Polymorphic):**
- `attachable_type`, `attachable_id` (morphs to incident or update)
- `uploaded_by` → users
- File metadata: `original_name`, `mime_type`, `size_bytes`, `disk`, `path`
- Auto-indexed on polymorphic columns

**Subscriptions Table (Polymorphic):**
- `subscribable_type`, `subscribable_id` (morphs to service or incident)
- `user_id` → users
- Unique constraint: `[user_id, subscribable_type, subscribable_id]`
- Auto-indexed on polymorphic columns

### Running Migrations

```bash
# Run all pending migrations
php artisan migrate

# Fresh migration (drops all tables, re-runs migrations)
php artisan migrate:fresh

# Check migration status
php artisan migrate:status

# Rollback last batch
php artisan migrate:rollback
```

### Important Notes

⚠️ **Polymorphic Indexes:** Laravel's `morphs()` method automatically creates indexes on `type` + `id` columns. Don't add duplicate indexes manually or you'll get this error:
```
index <table>_<name>_type_<name>_id_index already exists
```

✅ **Cloud Storage:** Attachments use `disk` + `path` columns for S3/R2 compatibility. Don't rely on local disk persistence in production.

---

## Authorization Policies

All models are protected by policies that enforce role-based access control. Policies centralize authorization logic and integrate with Laravel's `Gate` facade and `@can` Blade directives.

### Creating Policies

```bash
php artisan make:policy ServicePolicy --model=Service
php artisan make:policy IncidentPolicy --model=Incident
php artisan make:policy IncidentUpdatePolicy --model=IncidentUpdate
php artisan make:policy AttachmentPolicy --model=Attachment
php artisan make:policy SubscriptionPolicy --model=Subscription
```

### Policy Rules Summary

| Policy | viewAny / view | create | update | delete |
|--------|---------------|--------|--------|--------|
| **Service** | All users ✅ | Admin/Responder | Admin/Responder | Admin only |
| **Incident** | All users ✅ | Admin/Responder | Admin/Responder | Admin only |
| **IncidentUpdate** | All users ✅ | Admin/Responder | Admin/Responder | Admin only |
| **Attachment** | All users ✅ | Admin/Responder | Admin only | Admin or uploader |
| **Subscription** | All users ✅ | All users ✅ | Owner or Admin | Owner or Admin |

### Policy Implementation Details

**ServicePolicy, IncidentPolicy, IncidentUpdatePolicy:**
- All authenticated users can view (read-only access for viewers)
- Admins and responders can create and update
- Only admins can delete

**AttachmentPolicy:**
- All authenticated users can view/download
- Admins and responders can upload
- Admins or the original uploader can delete their own attachments
- Only admins can update metadata

**SubscriptionPolicy:**
- All authenticated users can subscribe/unsubscribe
- Users can only manage their own subscriptions
- Admins can manage any subscription

### Usage Examples

**In Controllers:**
```php
// Authorize before showing create form
public function create()
{
    $this->authorize('create', Incident::class);
    return view('incidents.create');
}

// Authorize before updating
public function update(Request $request, Incident $incident)
{
    $this->authorize('update', $incident);
    $incident->update($request->validated());
    return redirect()->route('incidents.show', $incident);
}
```

**In Blade Templates:**
```blade
{{-- Show create button only if authorized --}}
@can('create', App\Models\Incident::class)
    <a href="{{ route('incidents.create') }}">Create Incident</a>
@endcan

{{-- Show edit button only if authorized --}}
@can('update', $incident)
    <a href="{{ route('incidents.edit', $incident) }}">Edit</a>
@endcan

{{-- Show delete button only if authorized --}}
@can('delete', $incident)
    <form method="POST" action="{{ route('incidents.destroy', $incident) }}">
        @csrf @method('DELETE')
        <button>Delete</button>
    </form>
@endcan
```

**Admin Override:**

Admins automatically bypass all policy checks via `Gate::before()` in [AuthServiceProvider.php](app/Providers/AuthServiceProvider.php):

```php
Gate::before(function (User $user, string $ability) {
    return $user->isAdmin() ? true : null;
});
```