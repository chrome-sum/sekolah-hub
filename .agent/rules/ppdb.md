---
trigger: always_on
---

# PPDB Module Rules (Part 1)

Status: Final

---

## Purpose

PPDB menangani:

- Pendaftaran siswa baru
- Form dinamis
- Upload dokumen
- Verifikasi
- Pengumuman

---

## Academic Year Rules

- Satu active academic year
- Memiliki jadwal pendaftaran
- Memiliki jadwal pengumuman

---

## Admission Track Rules

- Satu academic year memiliki banyak track
- Setiap track memiliki form sendiri

Contoh:

- Zonasi
- Prestasi
- Afirmasi

---

## Form System Rules

Menggunakan:

Hybrid Dynamic Form (EAV terbatas)

Field definition:

admission_form_fields

Value storage:

registration_values

---

## Supported Field Types

- text
- textarea
- number
- date
- email
- phone
- select
- radio
- checkbox
- file
- heading
- description

---

## Validation Rules

Validation disimpan di field definition.

Bukan di controller.

# PPDB Module Rules (Part 2)

Status: Final

---

## Registration Rules

- Satu registration = satu peserta
- Registration terkait:
  - academic_year_id
  - admission_track_id

---

## Registration Lifecycle

Status:

- draft
- submitted
- under_review
- verified
- accepted
- rejected
- withdrawn

---

## Locking Rule

Setelah status:

- submitted

maka:

- data tidak boleh diubah
- hanya admin yang dapat melakukan update status

---

## Registration Values

Disimpan di:

registration_values

Aturan:

- Satu field = satu row value
- Support multiple type storage:
  - value_text
  - value_number
  - value_date
  - value_boolean

---

## Checkbox Rule

Checkbox disimpan sebagai JSON di:

value_text

Contoh:

```json
["Basket", "Pramuka", "PMR"]
```

---

## File Upload Rules

Dokumen disimpan di:

registration_documents

Aturan:

- File private
- Tidak bisa diakses langsung
- Harus melalui controller authorized

---

## Document Verification

Status:

- pending
- approved
- rejected

Verifier disimpan di:

verified_by

---

## Announcement Rules

- Pengumuman dilakukan per batch
- Bisa berdasarkan track atau seluruh tahun ajaran
- Status publikasi terkontrol oleh published_at

---

## Numbering Rule

Format:

PPDB-{YEAR}-{SEQ}

Contoh:

PPDB-2026-000001

Harus unik per academic year.

---

## Export Rules

- Export Excel menggunakan Laravel Excel
- Tidak boleh streaming besar tanpa chunking
- Harus compatible shared hosting memory limit

---

## Security Rules

- Rate limiting pada submit form
- CAPTCHA/Turnstile wajib pada form publik
- IP logging wajib
