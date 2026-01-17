# Database Schema

This diagram shows the complete database structure for the Incident Tracker application.

```mermaid
erDiagram
    users ||--o{ services : "owns"
    users ||--o{ incidents : "creates"
    users ||--o{ incident_updates : "creates"
    users ||--o{ attachments : "uploads"
    users ||--o{ subscriptions : "subscribes"
    
    services ||--o{ incidents : "has"
    services ||--o{ subscriptions : "watched_by"
    
    incidents ||--o{ incident_updates : "has"
    incidents ||--o{ attachments : "has"
    incidents ||--o{ subscriptions : "watched_by"
    
    incident_updates ||--o{ attachments : "has"

    users {
        bigint id PK
        string name
        string email UK
        string password
        string role "admin, responder, viewer"
        timestamp created_at
        timestamp updated_at
    }

    services {
        bigint id PK
        string name
        string slug UK
        text description
        string status "active, maintenance, deprecated"
        bigint owner_id FK "nullable"
        timestamp created_at
        timestamp updated_at
    }

    incidents {
        bigint id PK
        string title
        text description
        string status "open, investigating, mitigating, monitoring, resolved"
        string severity "p1, p2, p3, p4"
        bigint service_id FK
        bigint created_by FK
        timestamp resolved_at "nullable"
        timestamp created_at
        timestamp updated_at
    }

    incident_updates {
        bigint id PK
        bigint incident_id FK
        bigint created_by FK
        text message
        string status "nullable"
        timestamp created_at
        timestamp updated_at
    }

    attachments {
        bigint id PK
        string attachable_type "polymorphic"
        bigint attachable_id "polymorphic"
        bigint uploaded_by FK
        string original_name
        string mime_type
        bigint size_bytes
        string disk "s3, r2, public"
        string path
        timestamp created_at
        timestamp updated_at
    }

    subscriptions {
        bigint id PK
        bigint user_id FK
        string subscribable_type "polymorphic"
        bigint subscribable_id "polymorphic"
        timestamp created_at
        timestamp updated_at
    }
```

## Key Relationships

### One-to-Many
- **User** → Services (owner)
- **User** → Incidents (creator)
- **User** → Incident Updates (creator)
- **Service** → Incidents

### Polymorphic (One-to-Many)
- **Attachments** can belong to:
  - Incidents (`attachable_type` = 'App\Models\Incident')
  - Incident Updates (`attachable_type` = 'App\Models\IncidentUpdate')

- **Subscriptions** can belong to:
  - Services (`subscribable_type` = 'App\Models\Service')
  - Incidents (`subscribable_type` = 'App\Models\Incident')

## Indexes

- **services**: `status`, `slug` (unique)
- **incidents**: `status`, `severity`, `created_at`, `service_id`, `created_by`
- **incident_updates**: `incident_id`, `created_at`, `created_by`
- **attachments**: `[attachable_type, attachable_id]` (auto-created by morphs)
- **subscriptions**: `[user_id, subscribable_type, subscribable_id]` (unique), `[subscribable_type, subscribable_id]` (auto-created by morphs)

## Cascade Behaviors

- When a **Service** is deleted → all related Incidents are deleted (cascade)
- When an **Incident** is deleted → all Updates and Attachments are deleted (cascade)
- When a **User** is deleted:
  - Their owned Services set `owner_id` to NULL
  - Their created Incidents, Updates, and Attachments are deleted (cascade)
  - Their Subscriptions are deleted (cascade)
