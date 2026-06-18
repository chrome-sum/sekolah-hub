---
trigger: always_on
---

# .agent/rules/architecture.md

# Architecture Rules

Status: Final

## Architecture Style

Gunakan Modular Monolith.

Tidak menggunakan package modular tambahan.

Struktur:

app/Modules/

---

## Approved Modules

Modul yang disetujui:

- System
- Media
- CMS
- Gallery
- Contact
- Theme
- PPDB

Agent tidak boleh membuat modul baru tanpa persetujuan eksplisit.

---

## Standard Module Structure

Setiap modul mengikuti struktur berikut:

Actions
Contracts
DTOs
Events
Exceptions
Http
Listeners
Models
Policies
Providers
Repositories
Services
Support
database
routes
views

Tidak semua folder wajib digunakan.

Gunakan seperlunya.

---

## Dependency Flow

Ikuti dependency flow berikut:

Controller
→ Action
→ Service
→ Contract
→ Model

---

## Forbidden Dependencies

Dilarang:

Model lintas modul.

Business logic di Blade.

Business logic kompleks di Controller.

Akses langsung ke modul lain tanpa Contract atau Service.

---

## Cross Module Communication

Komunikasi lintas modul dilakukan melalui:

- Contract
- Service

Bukan melalui Model langsung.

Benar:

PPDB → MediaContract

Salah:

PPDB → Media Model

---

## Service Provider

Setiap modul memiliki Service Provider sendiri.

Provider bertanggung jawab terhadap:

- Route
- Migration
- View
- Binding
- Policy

---

## Routes

Pisahkan route admin dan publik.

Contoh:

routes/admin.php

routes/web.php

---

## Migrations

Migration disimpan di dalam modul.

Contoh:

CMS/database/migrations

PPDB/database/migrations

---

## Themes

Theme dibaca dari filesystem.

Bukan database.

Struktur:

themes/
