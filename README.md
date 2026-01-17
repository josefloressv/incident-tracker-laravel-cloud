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
- Laravel 9
- PHP 8.2+
- Database: Postgres or MySQL
- Cache/Queue: Redis-compatible (Valkey/Redis)
- Object Storage: S3-compatible (e.g., Cloudflare R2)

## Local Setup

### Requirements
- PHP 8.2+
- Composer
- Node.js + npm
- A local database (Postgres or MySQL)
- Redis (optional but recommended)

### Install
```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev