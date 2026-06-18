---
trigger: always_on
---

# Gallery Module Rules

Status: Final

---

## Purpose

Gallery digunakan untuk publikasi foto kegiatan sekolah.

---

## Album Rules

- Album dapat memiliki banyak media.
- Media dapat digunakan di banyak album.
- Album memiliki cover_media_id.

---

## Sorting Rules

- Urutan media ditentukan oleh sort_order.
- Default urutan ascending.

---

## Slug Rules

- Slug album wajib global unique.

---

## Publishing Rules

- Album memiliki status:
  - draft
  - published

---

## Media Rules

- Tidak boleh upload file langsung ke gallery.
- Semua file melalui Media Module.

---

## Display Rules

- Public gallery hanya menampilkan published album.
- Draft hanya visible di admin.
