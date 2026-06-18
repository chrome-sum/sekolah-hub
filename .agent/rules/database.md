---
trigger: always_on
---

# .agent/rules/database.md

# Database Rules

Status: Final

## Source of Truth

ERD Final adalah acuan utama.

Jangan mengubah struktur database tanpa persetujuan eksplisit.

---

## Database Engine

Kompatibel dengan:

- MySQL 5.7+
- MariaDB

Hindari fitur database yang tidak kompatibel.

---

## Foreign Key Policy

FK intra-modul:

DIIZINKAN.

FK lintas modul:

DILARANG.

---

## Cross Module References

Gunakan ID biasa tanpa FK.

Contoh:

featured_media_id

cover_media_id

verified_by

---

## Slug Rules

Seluruh entitas publik menggunakan slug.

Slug bersifat global unik.

---

## Soft Delete

Selective soft delete.

Gunakan hanya pada tabel yang telah diputuskan.

Jangan menambahkan soft delete tanpa persetujuan.

---

## Migration Rules

Migration harus:

- Reversible.
- Menggunakan Laravel Schema Builder.
- Memiliki nama yang jelas.

---

## Column Changes

Dilarang:

- Menghapus kolom final.
- Mengubah tipe data final.
- Menambah tabel baru.

Tanpa persetujuan eksplisit.

---

## JSON Usage

Gunakan JSON hanya jika telah diputuskan.

Contoh:

settings.value

admission_form_fields.options

registration_values.value_text untuk checkbox.

Jangan mengganti desain EAV final.

---

## Query Rules

Gunakan Eloquent terlebih dahulu.

Gunakan Query Builder jika memang diperlukan.

Optimalkan query yang kompleks.

Hindari N+1 query.

Gunakan eager loading secara bijak.
