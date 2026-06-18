---
trigger: always_on
---

---

## trigger: always_on

# Frontend Rules (Blade, Alpine.js, DaisyUI)

Status: Final

---

## Tech Stack

- Blade templating engine
- Alpine.js
- Tailwind CSS + DaisyUI
- TinyMCE (self-hosted)

---

## Blade Component Rules

Default: anonymous component.

Gunakan anonymous component untuk:

- UI element murni tanpa logic
- Button, badge, alert, card, input field

Gunakan class-based component hanya jika:

- Component membutuhkan logic PHP yang tidak bisa dihindari
- Contoh: media picker yang perlu query database

Jangan membuat class-based component untuk UI element sederhana.

---

## Blade Component Location

```text
resources/views/components/
├── admin/
└── public/
```

Pisahkan component admin dan public.

---

## Admin Layout Structure

Referensi: WordPress admin style.

```text
resources/views/layouts/
└── admin.blade.php

resources/views/components/admin/
├── sidebar.blade.php
├── topbar.blade.php
└── breadcrumb.blade.php
```

`admin.blade.php` bertanggung jawab atas:

- Shell utama
- Sidebar slot
- Topbar slot
- Content slot

---

## Sidebar Rules

- Sidebar collapsible (minimize ke icon-only)
- State collapse disimpan di Alpine.js
- Navigasi per modul

---

## DaisyUI Rules

Gunakan DaisyUI component sebagai dasar UI.

Contoh:

- `btn` untuk button
- `card` untuk card
- `table` untuk tabel data
- `modal` untuk dialog
- `drawer` untuk sidebar collapsible
- `badge` untuk status label
- `alert` untuk notifikasi

Jangan membuat custom CSS untuk hal yang sudah disediakan DaisyUI.

Gunakan Tailwind utility untuk adjustment minor di atas DaisyUI.

---

## Alpine.js Rules

### Inline `x-data`

Gunakan inline untuk:

- Logic sederhana dan terisolasi
- Kurang dari ~5 baris Alpine logic
- Tidak dipakai di tempat lain

Contoh:

```html
<div x-data="{ open: false }">
  <button @click="open = !open">Toggle</button>
  <div x-show="open">Content</div>
</div>
```

### Extract ke `Alpine.data()`

Gunakan extract untuk:

- Logic reusable (dipakai lebih dari satu tempat)
- Melibatkan fetch/axios
- Logic lebih dari ~5 baris

Contoh:

```js
Alpine.data("mediaPickerModal", () => ({
  open: false,
  selected: null,
  // ...
}));
```

File JS disimpan di:

```text
resources/js/
```

Di-bundle via Vite saat development.

Output `public/build/` di-commit ke repo.

---

## TinyMCE Rules

Self-hosted di:

```text
public/vendor/tinymce/
```

Load via:

```html
<script src="/vendor/tinymce/tinymce.min.js"></script>
```

Tidak menggunakan CDN eksternal.

Tidak menggunakan npm/Vite untuk TinyMCE.

Inisialisasi TinyMCE via Alpine.js atau script inline di Blade view yang relevan.

---

## Naming Convention

Blade view file: `kebab-case.blade.php`

Alpine.data name: `camelCase`

DaisyUI class: ikuti dokumentasi DaisyUI

---

## Anti-Pattern

Dilarang:

- Logic bisnis di Blade template
- Query database langsung di Blade
- Inline style kecuali tidak ada alternatif
- Membuat custom CSS untuk hal yang sudah ada di DaisyUI
- Load TinyMCE dari CDN eksternal
