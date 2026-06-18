---
trigger: always_on
---

# CMS Rules (Posts, Pages, Categories, Menus)

Status: Final

---

## Post Rules

- Posts adalah konten berita sekolah.
- Wajib memiliki slug global unique.
- Single author per post.
- Support draft, published, archived.

Fields penting:

- title
- slug
- content
- excerpt
- featured_media_id
- seo_title
- seo_description

---

## Category Rules

- Categories bersifat hierarkis.
- Satu post boleh memiliki banyak kategori.
- Tidak ada limit depth kategori.

---

## Page Rules

- Pages mendukung parent-child hierarchy.
- Pages adalah konten statis.
- Tidak wajib memiliki kategori.

---

## Menu Rules

- Menu bersifat multi instance.
- Menu item mendukung nested structure.
- Menu item bisa berupa:
  - URL custom
  - Page
  - Post
  - Category

---

## Slug Rules

- Semua entitas publik wajib slug.
- Slug harus global unique.

---

## Content Rules

- Content disimpan sebagai HTML (TinyMCE).
- Tidak boleh ada business logic di view.
- Escape output hanya jika diperlukan.

---

## SEO Rules

SEO fields disimpan langsung di tabel:

- seo_title
- seo_description

Tidak menggunakan plugin SEO engine.

---

## Media Usage

CMS tidak menyimpan file langsung.

Semua media melalui Media Module.
