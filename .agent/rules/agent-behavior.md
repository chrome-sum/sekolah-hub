---
trigger: always_on
---

# .agent/rules/agent-behavior.md

# Agent Behavior Rules

Status: Final

## Before Writing Code

Sebelum menulis kode:

1. Identifikasi modul yang terlibat.
2. Baca rules terkait.
3. Cek PRD jika menyentuh domain bisnis.
4. Cek ERD jika menyentuh database.
5. Verifikasi dokumentasi jika diperlukan.

Baru kemudian implementasi.

---

## Ambiguity Handling

Jika terdapat ambiguitas:

- Berhenti.
- Jelaskan opsi.
- Jelaskan trade-off.
- Minta keputusan eksplisit.

Jangan membuat asumsi diam-diam.

---

## Anti-Hallucination

Jangan mengarang.

Jika tidak yakin terhadap:

- API Laravel
- Package pihak ketiga
- Artisan command
- Composer syntax
- Konfigurasi server
- Best practice framework

maka:

1. Gunakan MCP Context7 untuk dokumentasi resmi.
2. Jika belum cukup, lakukan browsing internet.
3. Gunakan sumber resmi sebagai referensi.
4. Nyatakan jika informasi tidak dapat diverifikasi.

Dilarang membuat:

- Method fiktif
- Class fiktif
- Command fiktif
- Konfigurasi fiktif

---

## Scope Discipline

Kerjakan hanya yang diminta.

Jangan:

- Menambah fitur di luar scope.
- Melakukan refactor besar tanpa persetujuan.
- Mengubah PRD.
- Mengubah ERD.
- Mengubah ADR.

Tanpa persetujuan eksplisit.

---

## Documentation Awareness

Anggap dokumen berikut sebagai source of truth:

1. PRD Final
2. ERD Final
3. ADR/\*
4. GEMINI.md
5. .agent/rules/\*

Jika terdapat konflik:

ikuti urutan di atas.

---

## Code Generation Philosophy

Utamakan:

- Keterbacaan.
- Maintainability.
- Laravel convention.
- Shared hosting compatibility.

Hindari:

- Overengineering.
- Enterprise pattern yang tidak diperlukan.
- Dependency baru tanpa justifikasi.

---

## Output Expectations

Ketika menghasilkan solusi:

- Jelaskan asumsi secara eksplisit.
- Sebutkan trade-off jika ada.
- Tandai keputusan yang belum final.
- Minta klarifikasi jika diperlukan.

Jangan berpura-pura yakin ketika informasi tidak lengkap.
