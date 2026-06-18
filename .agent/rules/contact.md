---
trigger: always_on
---

# Contact Module Rules

Status: Final

---

## Purpose

Menangani pesan dari form kontak publik.

---

## Form Rules

Field:

- name
- email
- phone (optional)
- subject
- message

---

## Spam Protection

Wajib menggunakan:

- Cloudflare Turnstile

---

## Storage Rules

Setiap pesan disimpan sebagai:

contact_messages

---

## Status Rules

Status:

- unread
- read
- replied
- archived

---

## Email Rules

Default:

- Tidak auto-send email
- Email notification optional via settings

---

## Security Rules

- Simpan IP address
- Simpan user agent
- Rate limiting wajib

---

## Deletion Rules

Soft delete only.
