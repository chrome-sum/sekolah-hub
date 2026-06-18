---
trigger: always_on
---

# Storage Rules

Status: Final

---

## Storage Architecture

Menggunakan Laravel filesystem abstraction.

Disk:

- public
- private

---

## Public Storage

Digunakan untuk:

- Media CMS
- Gallery images
- Theme assets

Path:

storage/app/public/

---

## Private Storage

Digunakan untuk:

- PPDB documents
- Sensitive uploads

Path:

storage/app/private/

---

## Access Rule

Private file:

- Tidak boleh diakses langsung via URL
- Harus melalui controller authorized

---

## File Naming Rule

- Gunakan unique hash name
- Jangan gunakan original filename sebagai storage name
- Simpan original filename di database

---

## Folder Structure

Disarankan:

public/
media/
gallery/

private/
ppdb/

---

## Upload Rule

Semua upload wajib:

- validated mime type
- size limited
- sanitized
