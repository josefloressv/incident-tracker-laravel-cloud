# Incident Tracker — TODO (Master)

## Decisions Log
- [x] Starter kit: **Laravel Breeze (Blade + Tailwind)**

## Day 1 Checklist (Build the foundation)
### Setup
- [ ] Create Laravel 9 app repo
- [ ] Configure `.env` (DB, APP_URL)
- [ ] Install **Laravel Breeze (Blade)** and build assets
- [ ] Run migrations
- [ ] Verify auth flows: register/login/logout

### Roles + Authorization
- [ ] Add `role` to `users` table (string/enum)
- [ ] Create `App\Enums\Role` enum (admin/responder/viewer)
- [ ] Seed 3 users (admin/responder/viewer)
- [ ] Add `Gate::before` admin override
- [ ] Create Policies scaffold for core models

### Core Domain (models + migrations)
- [ ] Create models + migrations:
  - [ ] `Service`
  - [ ] `Incident`
  - [ ] `IncidentUpdate`
  - [ ] `Attachment`
  - [ ] `Subscription`
- [ ] Define relationships and key columns (status/severity/etc.)
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
