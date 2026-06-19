<?php

declare(strict_types=1);

namespace Tests\Feature\PPDB;

use App\Models\User;
use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\AdmissionFormField;
use App\Modules\PPDB\Models\Registration;
use App\Modules\PPDB\Models\RegistrationDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PPDBTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $guestUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Seed System permissions & roles
        $this->seed(\App\Modules\System\database\seeders\SystemSeeder::class);

        // 2. Retrieve users
        $this->superAdmin = User::where('email', 'hasbialaziz67@gmail.com')->first();
        $this->guestUser = User::factory()->create();

        // Use local disk for testing uploads
        Storage::fake('local');

        // Reset rate limits
        RateLimiter::clear('ppdb-submit|127.0.0.1');
    }

    /**
     * Test admin authorization for PPDB.
     */
    public function test_only_authorized_users_can_access_ppdb_admin(): void
    {
        // Guests cannot access PPDB registrations list
        $response = $this->actingAs($this->guestUser)->get(route('admin.ppdb.registrations.index'));
        $response->assertStatus(403);

        // Super Admin can access PPDB registrations list
        $response = $this->actingAs($this->superAdmin)->get(route('admin.ppdb.registrations.index'));
        $response->assertStatus(200);
    }

    /**
     * Test redirecting the root /admin/ppdb path to the registrations index route.
     */
    public function test_ppdb_admin_root_redirects_to_registrations_index(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/ppdb');
        $response->assertRedirect(route('admin.ppdb.registrations.index'));
    }

    /**
     * Test Academic Year CRUD and active constraints.
     */
    public function test_academic_year_crud_and_activation_constraints(): void
    {
        $this->actingAs($this->superAdmin);

        // 1. Create Academic Year
        $payload = [
            'name' => 'Tahun Ajaran 2026/2027',
            'code' => '2026/2027',
            'registration_open_at' => now()->subDays(5)->format('Y-m-d H:i:s'),
            'registration_close_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'announcement_at' => now()->addDays(15)->format('Y-m-d H:i:s'),
            'is_active' => '1',
        ];

        $response = $this->post(route('admin.ppdb.academic-years.store'), $payload);
        $response->assertRedirect(route('admin.ppdb.academic-years.index'));

        $this->assertDatabaseHas('academic_years', [
            'code' => '2026/2027',
            'is_active' => true,
        ]);

        $year1 = AcademicYear::where('code', '2026/2027')->first();

        // 2. Create another active Academic Year (should deactivate the first)
        $payload2 = [
            'name' => 'Tahun Ajaran 2027/2028',
            'code' => '2027/2028',
            'registration_open_at' => now()->addYear()->format('Y-m-d H:i:s'),
            'registration_close_at' => now()->addYear()->addMonth()->format('Y-m-d H:i:s'),
            'announcement_at' => now()->addYear()->addMonth()->addDays(10)->format('Y-m-d H:i:s'),
            'is_active' => '1',
        ];

        $response2 = $this->post(route('admin.ppdb.academic-years.store'), $payload2);
        $response2->assertRedirect(route('admin.ppdb.academic-years.index'));

        $year1->refresh();
        $this->assertFalse($year1->is_active);

        $this->assertDatabaseHas('academic_years', [
            'code' => '2027/2028',
            'is_active' => true,
        ]);

        $year2 = AcademicYear::where('code', '2027/2028')->first();

        // 3. Edit Academic Year
        $updatePayload = [
            'name' => 'Tahun Ajaran 2027/2028 (Revisi)',
            'code' => '2027/2028-rev',
            'registration_open_at' => now()->addYear()->format('Y-m-d H:i:s'),
            'registration_close_at' => now()->addYear()->addMonth()->format('Y-m-d H:i:s'),
            'announcement_at' => now()->addYear()->addMonth()->addDays(10)->format('Y-m-d H:i:s'),
            'is_active' => '1',
        ];

        $response3 = $this->put(route('admin.ppdb.academic-years.update', $year2->id), $updatePayload);
        $response3->assertRedirect(route('admin.ppdb.academic-years.index'));

        $this->assertDatabaseHas('academic_years', [
            'id' => $year2->id,
            'name' => 'Tahun Ajaran 2027/2028 (Revisi)',
            'code' => '2027/2028-rev',
        ]);

        // 4. Delete active academic year (should fail)
        $deleteResponse = $this->delete(route('admin.ppdb.academic-years.destroy', $year2->id));
        $deleteResponse->assertRedirect(route('admin.ppdb.academic-years.index'));
        $deleteResponse->assertSessionHas('error');
        $this->assertDatabaseHas('academic_years', ['id' => $year2->id]);

        // 5. Delete inactive academic year (should succeed)
        $deleteResponse2 = $this->delete(route('admin.ppdb.academic-years.destroy', $year1->id));
        $deleteResponse2->assertRedirect(route('admin.ppdb.academic-years.index'));
        $deleteResponse2->assertSessionHas('success');
        $this->assertDatabaseMissing('academic_years', ['id' => $year1->id]);
    }

    /**
     * Test Admission Track CRUD and slug generation.
     */
    public function test_admission_track_crud_and_slugs(): void
    {
        $this->actingAs($this->superAdmin);

        $academicYear = AcademicYear::create([
            'name' => 'Tahun Ajaran 2026/2027',
            'code' => '2026/2027',
            'is_active' => true,
        ]);

        // 1. Create Track
        $payload = [
            'academic_year_id' => $academicYear->id,
            'name' => 'Jalur Prestasi',
            'quota' => 50,
            'description' => 'Untuk siswa berprestasi',
            'is_active' => '1',
        ];

        $response = $this->post(route('admin.ppdb.tracks.store'), $payload);
        $response->assertRedirect(route('admin.ppdb.tracks.index'));

        $this->assertDatabaseHas('admission_tracks', [
            'name' => 'Jalur Prestasi',
            'slug' => 'jalur-prestasi',
            'quota' => 50,
        ]);

        $track = AdmissionTrack::where('slug', 'jalur-prestasi')->first();

        // 2. Create duplicate Track name (slug auto-uniquification)
        $response2 = $this->post(route('admin.ppdb.tracks.store'), $payload);
        $this->assertDatabaseHas('admission_tracks', [
            'name' => 'Jalur Prestasi',
            'slug' => 'jalur-prestasi-1',
        ]);
    }

    /**
     * Test Form Fields CRUD and reordering.
     */
    public function test_form_fields_crud_and_reordering(): void
    {
        $this->actingAs($this->superAdmin);

        $academicYear = AcademicYear::create([
            'name' => 'Tahun Ajaran 2026/2027',
            'code' => '2026/2027',
            'is_active' => true,
        ]);

        $track = AdmissionTrack::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Zonasi',
            'slug' => 'zonasi',
            'quota' => 100,
        ]);

        // 1. Create Form Field
        $payload = [
            'label' => 'Nama Lengkap Siswa',
            'field_key' => 'nama_lengkap',
            'type' => 'text',
            'placeholder' => 'Nama lengkap...',
            'help_text' => 'Sesuai akta',
            'is_required' => '1',
            'is_active' => '1',
        ];

        $response = $this->post(route('admin.ppdb.tracks.form-fields.store', $track->id), $payload);
        $response->assertRedirect(route('admin.ppdb.tracks.form-fields.index', $track->id));

        $this->assertDatabaseHas('admission_form_fields', [
            'track_id' => $track->id,
            'field_key' => 'nama_lengkap',
            'label' => 'Nama Lengkap Siswa',
            'is_required' => true,
        ]);

        $field1 = AdmissionFormField::where('track_id', $track->id)->where('field_key', 'nama_lengkap')->first();

        // 2. Create second field
        $payload2 = [
            'label' => 'Rapor Nilai',
            'field_key' => 'rapor_nilai',
            'type' => 'number',
            'is_required' => '0',
            'is_active' => '1',
        ];
        $this->post(route('admin.ppdb.tracks.form-fields.store', $track->id), $payload2);
        
        $field2 = AdmissionFormField::where('track_id', $track->id)->where('field_key', 'rapor_nilai')->first();

        // 3. Test reordering fields
        $reorderPayload = [
            'order' => [$field2->id, $field1->id]
        ];

        $reorderResponse = $this->post(route('admin.ppdb.tracks.form-fields.reorder', $track->id), $reorderPayload);
        $reorderResponse->assertRedirect(route('admin.ppdb.tracks.form-fields.index', $track->id));

        $field1->refresh();
        $field2->refresh();

        $this->assertEquals(0, $field2->sort_order);
        $this->assertEquals(1, $field1->sort_order);
    }

    /**
     * Test public registration form rendering, submission (EAV + Private Upload), and status check.
     */
    public function test_public_registration_flow(): void
    {
        // 1. Setup Active Year, Track, and Form Fields (Standard + File)
        $academicYear = AcademicYear::create([
            'name' => 'Tahun Ajaran 2026/2027',
            'code' => '2026/2027',
            'is_active' => true,
            'registration_open_at' => now()->subDays(2),
            'registration_close_at' => now()->addDays(5),
            'announcement_at' => now()->addDays(15),
        ]);

        $track = AdmissionTrack::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Zonasi',
            'slug' => 'zonasi',
            'quota' => 100,
            'is_active' => true,
        ]);

        $textField = AdmissionFormField::create([
            'track_id' => $track->id,
            'field_key' => 'nama_lengkap',
            'label' => 'Nama Lengkap',
            'type' => 'text',
            'is_required' => true,
            'sort_order' => 1,
        ]);

        $fileField = AdmissionFormField::create([
            'track_id' => $track->id,
            'field_key' => 'ijazah',
            'label' => 'Ijazah',
            'type' => 'file',
            'is_required' => true,
            'sort_order' => 2,
        ]);

        // 2. Render form page
        $response = $this->get(route('public.ppdb.register', $track->slug));
        $response->assertStatus(200);
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Ijazah');

        // 3. Submit registration (including file upload)
        $dummyFile = UploadedFile::fake()->create('ijazah_smp.pdf', 500, 'application/pdf');

        $payload = [
            'track_id' => $track->id,
            'fields' => [
                'nama_lengkap' => 'Ahmad Rian',
                'ijazah' => $dummyFile,
            ]
        ];

        $submitResponse = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->post(route('public.ppdb.submit', $track->slug), $payload);

        $submitResponse->assertRedirect(route('public.ppdb.status'));
        $submitResponse->assertSessionHas('success');

        // 4. Verify Database Registration
        $this->assertDatabaseHas('registrations', [
            'academic_year_id' => $academicYear->id,
            'track_id' => $track->id,
            'status' => 'submitted',
        ]);

        $registration = Registration::orderBy('id', 'desc')->first();
        $this->assertNotNull($registration);

        // Check EAV text value
        $this->assertDatabaseHas('registration_values', [
            'registration_id' => $registration->id,
            'field_id' => $textField->id,
            'value_text' => 'Ahmad Rian',
        ]);

        // Check file document record
        $this->assertDatabaseHas('registration_documents', [
            'registration_id' => $registration->id,
            'field_id' => $fileField->id,
            'original_name' => 'ijazah_smp.pdf',
            'verification_status' => 'pending',
        ]);

        $doc = RegistrationDocument::where('registration_id', $registration->id)->first();
        $this->assertNotNull($doc);

        // Verify storage file exists on private/local storage
        Storage::disk('local')->assertExists($doc->path);

        // 5. Check status page
        $statusResponse = $this->get(route('public.ppdb.status', [
            'registration_number' => $registration->registration_number
        ]));
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee($registration->registration_number);
        $statusResponse->assertSee('Ahmad Rian');
    }

    /**
     * Test registration submit rate limiting.
     */
    public function test_registration_rate_limiting(): void
    {
        $academicYear = AcademicYear::create([
            'name' => 'Tahun Ajaran 2026/2027',
            'code' => '2026/2027',
            'is_active' => true,
            'registration_open_at' => now()->subDays(2),
            'registration_close_at' => now()->addDays(5),
            'announcement_at' => now()->addDays(15),
        ]);

        $track = AdmissionTrack::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Zonasi',
            'slug' => 'zonasi',
            'is_active' => true,
        ]);

        $payload = [
            'track_id' => $track->id,
            'fields' => [
                'nama' => 'Test',
            ]
        ];

        // First 5 requests pass
        for ($i = 0; $i < 5; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
                ->post(route('public.ppdb.submit', $track->slug), $payload);
            $response->assertRedirect();
        }

        // 6th fails due to rate limiter
        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->post(route('public.ppdb.submit', $track->slug), $payload);

        $response->assertSessionHasErrors('fields.email');
    }

    /**
     * Test admin actions: Status update, Document verification, file download, and Excel export.
     */
    public function test_admin_registration_management_actions(): void
    {
        $this->actingAs($this->superAdmin);

        $academicYear = AcademicYear::create([
            'name' => 'Tahun Ajaran 2026/2027',
            'code' => '2026/2027',
            'is_active' => true,
        ]);

        $track = AdmissionTrack::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Zonasi',
            'slug' => 'zonasi',
        ]);

        $registration = Registration::create([
            'registration_number' => 'PPDB-2026-000001',
            'academic_year_id' => $academicYear->id,
            'track_id' => $track->id,
            'status' => 'submitted',
        ]);

        // 1. Update Status (Accept)
        $statusPayload = [
            'status' => 'accepted',
            'notes' => 'Selamat, Anda lulus!',
        ];

        $response = $this->put(route('admin.ppdb.registrations.update_status', $registration->id), $statusPayload);
        $response->assertRedirect(route('admin.ppdb.registrations.show', $registration->id));

        $registration->refresh();
        $this->assertEquals('accepted', $registration->status);
        $this->assertEquals('Selamat, Anda lulus!', $registration->notes);
        $this->assertNotNull($registration->accepted_at);

        // 2. Document verification
        $field = AdmissionFormField::create([
            'track_id' => $track->id,
            'field_key' => 'skl',
            'label' => 'SKL',
            'type' => 'file',
        ]);

        $dummyFile = UploadedFile::fake()->create('skl.pdf', 500, 'application/pdf');
        $storedPath = $dummyFile->store('ppdb', 'local');

        $doc = RegistrationDocument::create([
            'registration_id' => $registration->id,
            'field_id' => $field->id,
            'original_name' => 'skl.pdf',
            'stored_name' => basename($storedPath),
            'mime_type' => 'application/pdf',
            'extension' => 'pdf',
            'size' => 500000,
            'path' => $storedPath,
            'verification_status' => 'pending',
        ]);

        $verifyPayload = [
            'verification_status' => 'approved',
            'verification_notes' => 'Dokumen sah.',
        ];

        $verifyResponse = $this->put(route('admin.ppdb.documents.verify', $doc->id), $verifyPayload);
        $verifyResponse->assertRedirect(route('admin.ppdb.registrations.show', $registration->id));

        $doc->refresh();
        $this->assertEquals('approved', $doc->verification_status);
        $this->assertEquals('Dokumen sah.', $doc->verification_notes);

        // 3. Download Document
        $downloadResponse = $this->get(route('admin.ppdb.documents.download', $doc->id));
        $downloadResponse->assertStatus(200);
        $this->assertStringContainsString('skl.pdf', $downloadResponse->headers->get('Content-Disposition') ?? '');

        // 4. Export Registrations to Excel
        $exportResponse = $this->get(route('admin.ppdb.registrations.export', ['track_id' => $track->id]));
        $exportResponse->assertStatus(200);
        $this->assertStringContainsString('pendaftar-zonasi-', $exportResponse->headers->get('Content-Disposition') ?? '');
        // Note: Carbon/time is faked or formatted, but it returns download stream
    }
}
