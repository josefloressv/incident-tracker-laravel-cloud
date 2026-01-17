# GitHub Copilot Repository Instructions — Incident Tracker (Laravel 9 POC)

## Project context
This repository is a Laravel 9 proof-of-concept called **Incident Tracker**.
The goal is to build a small but production-ish app with:
- Auth starter kit + roles (admin/responder/viewer)
- Core models: Service, Incident, IncidentUpdate, Attachment, Subscription
- CRUD for services/incidents/updates + attachments upload
- Authorization via Policies
- Events/Listeners + queued Jobs for notifications
- Scheduler for daily digest or stale incident reminders (use `onOneServer()`)
- Cloud-ready patterns: managed DB, Redis/Valkey cache, S3/R2 object storage, queues/workers, scheduler, logs

## Hard requirements
- All code, variable names, database naming, and docs must be **English**.
- Prefer **Laravel conventions** over custom patterns.
- Keep changes **small and incremental** (2-day constraint).
- When generating code, include:
  - exact file paths
  - complete class definitions (not fragments), unless asked otherwise
  - minimal, working implementations

## Tech & conventions
- Framework: Laravel 9
- PHP: 8.2+
- UI: Blade + Tailwind (keep views simple)
- Data: Postgres or MySQL (use standard Laravel migrations)
- Queue/Cache: Redis-compatible (Valkey/Redis)
- Storage: S3-compatible (Cloudflare R2) — avoid assuming local disk persistence in production

## Coding style
- Follow PSR-12 formatting.
- Use strict, descriptive names:
  - Models: `Service`, `Incident`, `IncidentUpdate`, `Attachment`, `Subscription`
  - Tables: `services`, `incidents`, `incident_updates`, `attachments`, `subscriptions`
- Keep controllers thin:
  - validation via Form Requests
  - business logic can live in small Actions/Services only if needed (avoid over-engineering)
- Prefer Eloquent relationships and query scopes for filters.

## Authorization
- Implement authorization using Policies:
  - `ServicePolicy`, `IncidentPolicy`, `IncidentUpdatePolicy`, `AttachmentPolicy`, `SubscriptionPolicy`
- Default rules:
  - admin: full access
  - responder: create incidents, add updates, change status, upload attachments
  - viewer: read-only
- Use `authorize()` / `Gate::allows()` in controllers and `@can` in Blade.

## Database modeling guidance
- Use enums/strings for:
  - incident `status`: `open`, `investigating`, `mitigating`, `monitoring`, `resolved`
  - incident `severity`: `p1`, `p2`, `p3`, `p4`
- Always add:
  - foreign keys with indexes
  - `created_by` on incidents and updates
- Prefer polymorphic subscriptions:
  - `subscriptions`: `user_id`, `subscribable_type`, `subscribable_id`

## File uploads (attachments)
- Store metadata: `original_name`, `mime_type`, `size_bytes`, `disk`, `path`.
- Use Laravel Storage APIs (`Storage::disk(...)`) and avoid direct filesystem calls.
- Assume production uses S3/R2; do not rely on local disk being persistent.

## Events, jobs, notifications
- Use Events + Listeners to decouple:
  - Event examples: `IncidentCreated`, `IncidentStatusChanged`, `IncidentUpdateAdded`
  - Listener dispatches a queued Job: `NotifySubscribers`
- Queue jobs should:
  - be idempotent where possible
  - use `ShouldQueue`
  - handle missing records gracefully (deleted incidents, etc.)
- Prefer Database notifications (and optionally Mail) for speed.

## Scheduler
- Add one scheduled command:
  - daily digest of open incidents OR stale incident reminder
- Use `onOneServer()` to avoid duplicates in multi-replica environments.

## Testing
- Prefer feature tests for critical flows:
  - responders can create incident
  - viewers cannot create incident
  - attachments upload works
- Use factories; keep tests minimal but meaningful.

## Documentation
- Keep `README.md` concise:
  - local setup commands
  - env vars needed (DB/Redis/S3)
  - how to run queue + scheduler locally
  - basic usage notes

## What to avoid
- Avoid adding new frameworks or heavy abstractions.
- Avoid complex DDD layers.
- Avoid long refactors; prefer minimal diffs that compile and pass tests.