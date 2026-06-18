---
trigger: always_on
---

# Auth & Roles Rules

Status: Final

## Authentication Stack

Menggunakan:

- Laravel Breeze (Blade stack)
- Session-based authentication
- MySQL/MariaDB

Tidak menggunakan:

- OAuth
- JWT
- Token-based auth untuk web admin

---

## Roles System

Menggunakan Spatie Laravel Permission.

Roles default:

- Super Admin
- Admin Sekolah
- Editor

---

## Role Rules

### Super Admin

- Full access sistem
- Mengelola konfigurasi global
- Mengelola semua modul

### Admin Sekolah

- Mengelola konten sekolah
- Mengelola PPDB
- Mengelola media

### Editor

- Mengelola CMS (posts/pages)
- Tidak bisa akses konfigurasi sistem

---

## Permission Rules

Gunakan permission granular dari Spatie.

Contoh:

- post.create
- post.update
- ppdb.manage
- media.upload

Jangan hardcode role check di controller.

---

## Authorization Rule

Wajib menggunakan Policy atau Gate.

Benar:

```php
$this->authorize('update', $post);
```

Salah:

```php
if (auth()->user()->role === 'admin') {
}
```

---

## Module Access Control

Setiap modul wajib mendefinisikan:

- Policy
- Permission mapping

Tidak boleh ada akses langsung tanpa authorization check.

---

## Multi-School Context

V1 adalah single-tenant per instalasi.

Tidak ada multi-school dalam satu database.

---

## Session Rules

- Session-based auth
- Remember me diperbolehkan
- Logout menghapus session
