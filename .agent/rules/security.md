---
trigger: always_on
---

# Security Rules

Status: Final

---

## Input Validation

Semua input wajib:

- menggunakan Form Request
- tidak boleh validasi di controller langsung

---

## Authorization

Wajib menggunakan Policy atau Gate.

Dilarang:

- role check manual di controller
- logic authorization di view

---

## Output Escaping

Blade template:

- escape default ON
- gunakan {!! !!} hanya jika aman

---

## CSRF Protection

- wajib aktif
- tidak boleh dimatikan

---

## Rate Limiting

Wajib pada:

- login
- contact form
- PPDB submission

---

## File Upload Security

- whitelist mime type
- limit size
- sanitize filename
- scan basic malicious file signature (basic level)

---

## Access Control

- Private storage tidak boleh direct access
- Harus melalui controller + authorization

---

## Sensitive Data

Dilarang menyimpan:

- password plaintext
- token di log
- data sensitif di audit log tanpa masking

---

## Logging Rules

- audit log tidak boleh menyimpan password/token
- gunakan masking untuk data sensitif

---

## Dependency Security

- jangan menambah dependency tanpa justifikasi
- hindari package tidak maintained
