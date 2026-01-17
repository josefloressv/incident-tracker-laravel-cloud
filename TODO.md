# Incident Tracker — TODO (Master)

## Decisions Log
- [x] Starter kit: **Laravel Breeze (Blade + Tailwind)**

## Day 1 Checklist (Build the foundation)
### Setup
- [ x ] Create Laravel 9 app repo
- [ x ] Configure `.env` (DB, APP_URL)
- [ x ] Install **Laravel Breeze (Blade)** and build assets
- [ x ] Run migrations
- [ x ] Verify auth flows: register/login/logout

### Roles + Authorization
- [ x ] Add `role` to `users` table (string/enum)
- [ x ] Create `App\Enums\Role` enum (admin/responder/viewer)
- [ x ] Seed 3 users (admin/responder/viewer)
- [ x ] Add `Gate::before` admin override
- [ x ] Create Policies scaffold for core models

### Core Domain (models + migrations)
- [ x ] Create models + migrations:
  - [ x ] `Service`
  - [ x ] `Incident`
  - [ x ] `IncidentUpdate`
  - [ x ] `Attachment`
  - [ x ] `Subscription`
- [ x ] Define relationships and key columns (status/severity/etc.)
- [ ] Run migrations + seed sample Services/Incidents
- [ ] Verify relationships in Tinker

### Progress Notes (Day 1)
- [ ] Summary bullets (fill in after each completed chunk)

---

## Day 2 Checklist (Finish features + cloud-ready patterns)
### CRUD UI
- [ ] CRUD: Services
- [ ] CRUD: Incidents
- [ ] Incident Updates UI (timeline/comments st
