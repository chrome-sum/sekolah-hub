# ERD Master — CMS Sekolah + PPDB V1

## Informasi Umum

- Arsitektur: Modular Monolith
- Framework: Laravel
- Target Deployment: Shared Hosting
- Database: MySQL / MariaDB
- Soft Delete: Selective
- Permission: Spatie Laravel Permission
- Theme Engine: Level 1–2
- PPDB: Hybrid Dynamic Form (EAV Terbatas)

---

# SYSTEM MODULE

```text
users
├─ id (PK)
├─ name
├─ email (UNIQUE)
├─ email_verified_at
├─ password
├─ remember_token
├─ created_at
└─ updated_at
```

```text
settings
├─ id (PK)
├─ key (UNIQUE)
├─ value (LONGTEXT)
├─ description (NULL)
├─ created_at
└─ updated_at
```

```text
audit_logs
├─ id (PK)
├─ user_id (NULL)
├─ action
├─ auditable_type
├─ auditable_id
├─ old_values (JSON NULL)
├─ new_values (JSON NULL)
├─ ip_address (NULL)
├─ user_agent (NULL)
└─ created_at
```

```text
jobs
failed_jobs
```

```text
spatie_permission_tables
├─ roles
├─ permissions
├─ model_has_roles
├─ model_has_permissions
└─ role_has_permissions
```

---

# MEDIA MODULE

```text
media_folders
├─ id (PK)
├─ parent_id (NULL)
├─ name
├─ slug
├─ created_by (NULL)
├─ updated_by (NULL)
├─ created_at
├─ updated_at
└─ deleted_at
```

```text
media
├─ id (PK)
├─ folder_id (NULL)
├─ disk
├─ path
├─ filename
├─ original_name
├─ extension
├─ mime_type
├─ size
├─ width (NULL)
├─ height (NULL)
├─ alt_text (NULL)
├─ caption (NULL)
├─ uploaded_by (NULL)
├─ created_at
├─ updated_at
└─ deleted_at
```

```text
media_variants
├─ id (PK)
├─ media_id
├─ variant
├─ path
├─ width
├─ height
├─ size
├─ created_at
└─ updated_at
```

## Relasi

```text
media_folders
    1
    └──< media

media
    1
    └──< media_variants
```

---

# CMS MODULE

## POSTS

```text
posts
├─ id (PK)
├─ title
├─ slug (UNIQUE)
├─ excerpt (NULL)
├─ content (LONGTEXT)
├─ featured_media_id (NULL)
├─ status
├─ published_at (NULL)
├─ author_id (NULL)
├─ seo_title (NULL)
├─ seo_description (NULL)
├─ created_at
├─ updated_at
└─ deleted_at
```

## CATEGORIES

```text
categories
├─ id (PK)
├─ parent_id (NULL)
├─ name
├─ slug (UNIQUE)
├─ description (NULL)
├─ created_at
├─ updated_at
└─ deleted_at
```

## POST CATEGORIES

```text
post_categories
├─ post_id
└─ category_id
```

## PAGES

```text
pages
├─ id (PK)
├─ parent_id (NULL)
├─ title
├─ slug (UNIQUE)
├─ content (LONGTEXT)
├─ featured_media_id (NULL)
├─ status
├─ seo_title (NULL)
├─ seo_description (NULL)
├─ created_at
├─ updated_at
└─ deleted_at
```

## MENUS

```text
menus
├─ id (PK)
├─ name
├─ slug
├─ location
├─ created_at
└─ updated_at
```

## MENU ITEMS

```text
menu_items
├─ id (PK)
├─ menu_id
├─ parent_id (NULL)
├─ title
├─ type
├─ reference_type (NULL)
├─ reference_id (NULL)
├─ url (NULL)
├─ target
├─ sort_order
├─ created_at
└─ updated_at
```

## Relasi

```text
categories
    1
    └──< categories

posts
    └──< post_categories >──┐
                             │
categories ─────────────────┘

pages
    1
    └──< pages

menus
    1
    └──< menu_items

menu_items
    1
    └──< menu_items
```

---

# GALLERY MODULE

```text
gallery_albums
├─ id (PK)
├─ title
├─ slug (UNIQUE)
├─ description (NULL)
├─ cover_media_id (NULL)
├─ status
├─ published_at (NULL)
├─ created_by (NULL)
├─ updated_by (NULL)
├─ created_at
├─ updated_at
└─ deleted_at
```

```text
gallery_album_items
├─ id (PK)
├─ album_id
├─ media_id
├─ caption (NULL)
├─ sort_order
└─ created_at
```

## Relasi

```text
gallery_albums
    1
    └──< gallery_album_items

gallery_album_items
    ── media_id (Reference Only)
```

---

# CONTACT MODULE

```text
contact_messages
├─ id (PK)
├─ name
├─ email
├─ phone (NULL)
├─ subject
├─ message
├─ status
├─ replied_at (NULL)
├─ ip_address (NULL)
├─ user_agent (NULL)
├─ created_at
├─ updated_at
└─ deleted_at
```

---

# THEME MODULE

Tidak memiliki tabel khusus.

Menggunakan tabel:

```text
settings
```

Contoh key:

```text
theme.active
theme.homepage_sections
theme.settings
```

---

# PPDB MODULE

## ACADEMIC YEARS

```text
academic_years
├─ id (PK)
├─ name
├─ code (UNIQUE)
├─ is_active
├─ registration_open_at (NULL)
├─ registration_close_at (NULL)
├─ announcement_at (NULL)
├─ created_by (NULL)
├─ updated_by (NULL)
├─ created_at
└─ updated_at
```

## ADMISSION TRACKS

```text
admission_tracks
├─ id (PK)
├─ academic_year_id
├─ name
├─ slug (UNIQUE)
├─ quota (NULL)
├─ description (NULL)
├─ is_active
├─ created_at
└─ updated_at
```

## FORM FIELDS

```text
admission_form_fields
├─ id (PK)
├─ track_id
├─ field_key
├─ label
├─ type
├─ placeholder (NULL)
├─ help_text (NULL)
├─ is_required
├─ options (JSON NULL)
├─ validation_rules (NULL)
├─ sort_order
├─ is_active
├─ created_at
└─ updated_at
```

## REGISTRATIONS

```text
registrations
├─ id (PK)
├─ registration_number (UNIQUE)
├─ academic_year_id
├─ track_id
├─ status
├─ submitted_at (NULL)
├─ verified_at (NULL)
├─ accepted_at (NULL)
├─ rejected_at (NULL)
├─ announcement_published_at (NULL)
├─ locked_at (NULL)
├─ notes (NULL)
├─ created_at
└─ updated_at
```

## REGISTRATION VALUES

```text
registration_values
├─ id (PK)
├─ registration_id
├─ field_id
├─ value_text (NULL)
├─ value_number (NULL)
├─ value_date (NULL)
├─ value_boolean (NULL)
├─ created_at
└─ updated_at
```

## REGISTRATION DOCUMENTS

```text
registration_documents
├─ id (PK)
├─ registration_id
├─ field_id (NULL)
├─ original_name
├─ stored_name
├─ mime_type
├─ extension
├─ size
├─ path
├─ verification_status
├─ verification_notes (NULL)
├─ verified_by (NULL)
├─ verified_at (NULL)
├─ created_at
└─ updated_at
```

## ANNOUNCEMENT BATCHES

```text
announcement_batches
├─ id (PK)
├─ academic_year_id
├─ track_id (NULL)
├─ name
├─ published_at (NULL)
├─ created_by (NULL)
└─ created_at
```

## Relasi

```text
academic_years
    1
    ├──< admission_tracks
    │           │
    │           ├──< admission_form_fields
    │           │
    │           └──< registrations
    │                       │
    │                       ├──< registration_values
    │                       │           │
    │                       │           └── admission_form_fields
    │                       │
    │                       └──< registration_documents
    │                                   │
    │                                   └── admission_form_fields
    │
    └──< announcement_batches
```

---

# CROSS MODULE REFERENCES

Reference only (tanpa FK lintas modul):

```text
posts.featured_media_id
pages.featured_media_id
gallery_albums.cover_media_id
gallery_album_items.media_id
registration_documents.verified_by
audit_logs.user_id
```

Prinsip:

- FK hanya di dalam modul yang sama.
- Antar modul menggunakan service/contract.
- Menjaga modular monolith tetap longgar (loosely coupled).

---

# STATUS

Semua ERD V1 telah FINAL.

```text
SYSTEM     ✅
MEDIA      ✅
CMS        ✅
GALLERY    ✅
CONTACT    ✅
THEME      ✅
PPDB       ✅
```
