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

### Install Breeze (Blade)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade

npm install
npm run dev

php artisan migrate
```
