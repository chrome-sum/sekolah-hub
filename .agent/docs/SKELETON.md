# Skeleton Proyek Laravel Modular Monolith

## Root Structure

```text
project/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
├── themes/
├── app/Modules/
└── composer.json
```

---

# app Structure

```text
app/
├── Console/
├── Exceptions/
├── Http/
├── Models/
├── Providers/
├── Support/
├── Modules/
└── View/
```

---

# Modules Structure

```text
app/Modules/
├── System/
├── Media/
├── CMS/
├── Gallery/
├── Contact/
├── Theme/
└── PPDB/
```

---

# Standard Module Structure

Setiap modul mengikuti struktur yang sama.

Contoh:

```text
app/Modules/CMS/
├── Actions/
├── Contracts/
├── DTOs/
├── Events/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── Public/
│   ├── Requests/
│   └── Resources/
├── Listeners/
├── Models/
├── Policies/
├── Providers/
├── Repositories/
├── Services/
├── Support/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── admin.php
│   └── web.php
└── views/
```

---

# Namespace Convention

## Module Namespace

CMS:

```php
App\Modules\CMS\
```

PPDB:

```php
App\Modules\PPDB\
```

Media:

```php
App\Modules\Media\
```

---

## Examples

Controller:

```php
App\Modules\CMS\Http\Controllers\Admin\PostController
```

Action:

```php
App\Modules\CMS\Actions\CreatePostAction
```

Service:

```php
App\Modules\CMS\Services\PostService
```

Contract:

```php
App\Modules\CMS\Contracts\PostRepositoryInterface
```

Model:

```php
App\Modules\CMS\Models\Post
```

---

# Service Provider Structure

Setiap modul memiliki provider sendiri.

Contoh:

```text
app/Modules/CMS/Providers/
└── CMSServiceProvider.php
```

---

## CMSServiceProvider

Bertanggung jawab terhadap:

```text
Register bindings
Load routes
Load migrations
Load views
Load translations
Register policies
```

---

Contoh:

```php
class CMSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
```

---

# Module Registration

## app/Providers/ModuleServiceProvider.php

Tugas:

Memuat seluruh provider modul.

```text
System
Media
CMS
Gallery
Contact
Theme
PPDB
```

---

Kemudian didaftarkan pada:

```php
config/app.php
```

---

# Routing Structure

## Admin

```text
app/Modules/CMS/routes/admin.php
```

Contoh:

```php
Route::middleware(['web','auth'])
    ->prefix('admin')
    ->group(function () {

    });
```

---

## Public

```text
app/Modules/CMS/routes/web.php
```

---

# Controller Pattern

Controller hanya orchestration.

Contoh:

```php
public function store(StorePostRequest $request)
{
    return $this->createPostAction->execute(
        $request->validated()
    );
}
```

Controller:

```text
Validation
Authorization
Call Action
Response
```

Tidak berisi business logic.

---

# Actions

## Filosofi

Satu action = satu use case.

---

Contoh CMS:

```text
CreatePostAction
UpdatePostAction
DeletePostAction
PublishPostAction
```

---

Contoh PPDB:

```text
CreateRegistrationAction
SubmitRegistrationAction
VerifyRegistrationAction
PublishAnnouncementAction
UploadDocumentAction
```

---

Contoh Media:

```text
UploadMediaAction
GenerateVariantsAction
DeleteMediaAction
RestoreMediaAction
```

---

# Services

## Filosofi

Business process yang lebih besar.

Boleh memanggil beberapa action.

---

Contoh:

```text
PPDBService
MediaService
ThemeService
```

---

Misal:

```text
PublishAnnouncementService

├─ VerifyBatchAction
├─ UpdateRegistrationStatusAction
└─ DispatchNotificationAction
```

---

# Contracts

Dipakai untuk boundary.

Contoh:

```text
MediaUrlGeneratorInterface
MediaRepositoryInterface

RegistrationExporterInterface

ThemeResolverInterface
```

---

Contoh:

```php
interface ThemeResolverInterface
{
    public function active(): string;
}
```

---

# Repositories

## Dipakai Secara Selektif

Tidak wajib.

Gunakan hanya jika:

```text
Query kompleks
Query reusable
Cross-table query
```

---

Tidak perlu:

```text
PostRepository::create()
PostRepository::update()
```

untuk CRUD sederhana.

---

# DTOs

Dipakai untuk use case kompleks.

Terutama PPDB.

Contoh:

```text
RegistrationData
AnnouncementData
DocumentVerificationData
```

---

# Requests

Validation dipisahkan.

Contoh:

```text
StorePostRequest
UpdatePostRequest

StoreRegistrationRequest
SubmitRegistrationRequest

UploadDocumentRequest
```

---

# Policies

Setiap modul memiliki policy sendiri.

Contoh:

```text
PostPolicy
PagePolicy

RegistrationPolicy
AcademicYearPolicy

MediaPolicy
```

---

# Events & Listeners

Dipakai untuk side effect.

Contoh:

```text
RegistrationSubmitted
RegistrationVerified

ContactMessageReceived

MediaUploaded
```

---

Listener:

```text
GenerateMediaVariants
SendEmailNotification
WriteAuditLog
```

---

# Models

Disimpan per modul.

Contoh:

```text
CMS/Models/Post.php
PPDB/Models/Registration.php
Media/Models/Media.php
```

---

# Migration Structure

Migration tetap modular.

Contoh:

```text
app/Modules/CMS/database/migrations/
app/Modules/PPDB/database/migrations/
app/Modules/Media/database/migrations/
```

---

Contoh penamaan:

```text
2026_01_01_000001_create_posts_table.php
2026_01_01_000002_create_pages_table.php

2026_01_02_000001_create_academic_years_table.php
```

---

# Seeder Structure

```text
database/seeders/
├── DatabaseSeeder.php
└── ModuleSeeder.php
```

Modul:

```text
CMS/database/seeders/
PPDB/database/seeders/
System/database/seeders/
```

---

# Themes

```text
themes/
└── school-classic/
    ├── theme.json
    ├── assets/
    ├── views/
    ├── screenshots/
    └── components/
```

---

# Storage Structure

```text
storage/app/
├── private/
│   └── ppdb/
└── public/
    └── media/
```

---

# Public Structure

```text
public/
├── storage/
└── themes/
```

---

# Testing Structure

```text
tests/
├── Feature/
│   ├── CMS/
│   ├── Media/
│   ├── Contact/
│   └── PPDB/
└── Unit/
    ├── Actions/
    ├── Services/
    └── Policies/
```

---

# Dependency Rules (Penting)

## Diizinkan

```text
Controller
    ↓
Action
    ↓
Service
    ↓
Model
```

```text
Action
    ↓
Contract
```

```text
Listener
    ↓
Action
```

---

## Tidak Diizinkan

```text
CMS Model
    ↓ langsung
PPDB Model
```

```text
Controller
    ↓ langsung
Model kompleks
```

```text
View
    ↓
Business Logic
```

---

# Modul Final

```text
System
Media
CMS
Gallery
Contact
Theme
PPDB
```

---

# Filosofi Akhir

Skeleton ini dirancang untuk memenuhi tiga tujuan utama:

1. **Sederhana untuk shared hosting** — tetap Laravel standar tanpa dependency modular tambahan.
2. **Cukup modular untuk berkembang** — tiap modul memiliki boundary yang jelas.
3. **Mudah dipahami developer Laravel biasa** — sehingga maintenance dan onboarding lebih mudah.

Dengan skeleton ini, fondasi implementasi V1 sudah lengkap: mulai dari PRD, ERD, hingga struktur kode yang siap diterjemahkan menjadi migration dan implementasi fitur.
