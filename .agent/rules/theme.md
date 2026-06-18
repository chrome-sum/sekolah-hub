---
trigger: always_on
---

# Theme Module Rules

Status: Final

---

## Architecture

Theme berbasis filesystem.

Tidak menggunakan database table khusus theme.

---

## Theme Structure

themes/{theme-name}/

- theme.json
- views/
- assets/
- components/

---

## Active Theme

Active theme disimpan di:

settings table

key:
theme.active

---

## Homepage Rules

Homepage disusun dari JSON settings:

theme.homepage_sections

---

## Section Rules

Section predefined:

- hero
- announcement
- news
- gallery
- ppdb
- contact
- cta

---

## Rules

- Tidak ada visual builder.
- Tidak ada plugin system.
- Tidak ada dynamic section creation oleh admin.
