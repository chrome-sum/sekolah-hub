<?php

declare(strict_types=1);

namespace Tests\Feature\Gallery;

use App\Models\User;
use App\Modules\Gallery\Models\GalleryAlbum;
use App\Modules\Gallery\Models\GalleryAlbumItem;
use App\Modules\Media\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $editor;
    protected User $guestUser;
    protected Media $dummyMedia1;
    protected Media $dummyMedia2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Seed System permissions & roles
        $this->seed(\App\Modules\System\database\seeders\SystemSeeder::class);

        // 2. Retrieve/Create users
        $this->superAdmin = User::where('email', 'hasbialaziz67@gmail.com')->first();
        
        $this->editor = User::factory()->create();
        $this->editor->assignRole('Editor');

        $this->guestUser = User::factory()->create();

        // 3. Create dummy media items for testing
        $this->dummyMedia1 = Media::create([
            'disk' => 'public',
            'path' => 'media/test_image_1.jpg',
            'filename' => 'test_image_1.jpg',
            'original_name' => 'Image 1.jpg',
            'extension' => 'jpg',
            'mime_type' => 'image/jpeg',
            'size' => 10240,
            'width' => 800,
            'height' => 600,
        ]);

        $this->dummyMedia2 = Media::create([
            'disk' => 'public',
            'path' => 'media/test_image_2.jpg',
            'filename' => 'test_image_2.jpg',
            'original_name' => 'Image 2.jpg',
            'extension' => 'jpg',
            'mime_type' => 'image/jpeg',
            'size' => 20480,
            'width' => 1024,
            'height' => 768,
        ]);
    }

    /**
     * Test authorization for Gallery Admin endpoints.
     */
    public function test_only_authorized_users_can_access_gallery_admin(): void
    {
        // Guests cannot access gallery list
        $response = $this->actingAs($this->guestUser)->get(route('admin.gallery.index'));
        $response->assertStatus(403);

        // Editor can access gallery list
        $response = $this->actingAs($this->editor)->get(route('admin.gallery.index'));
        $response->assertStatus(200);

        // Super Admin can access gallery list
        $response = $this->actingAs($this->superAdmin)->get(route('admin.gallery.index'));
        $response->assertStatus(200);
    }

    /**
     * Test Album creation, automatic slug generation, and audit logging.
     */
    public function test_album_creation_and_audit_logging(): void
    {
        $this->actingAs($this->superAdmin);

        $albumData = [
            'title' => 'Studi Banding Guru 2026',
            'slug' => 'studi-banding-guru-2026',
            'description' => 'Dokumentasi kunjungan studi banding ke Bandung.',
            'cover_media_id' => $this->dummyMedia1->id,
            'status' => 'published',
        ];

        $response = $this->post(route('admin.gallery.store'), $albumData);
        $response->assertRedirect(route('admin.gallery.index'));

        // Verify database
        $this->assertDatabaseHas('gallery_albums', [
            'title' => 'Studi Banding Guru 2026',
            'slug' => 'studi-banding-guru-2026',
            'status' => 'published',
            'cover_media_id' => $this->dummyMedia1->id,
            'created_by' => $this->superAdmin->id,
        ]);

        $album = GalleryAlbum::where('slug', 'studi-banding-guru-2026')->first();
        $this->assertNotNull($album);

        // Check Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'gallery.album.create',
            'user_id' => $this->superAdmin->id,
            'auditable_type' => GalleryAlbum::class,
            'auditable_id' => $album->id,
        ]);

        // Test Duplicate Title Album (Slug auto-uniquification)
        $duplicateResponse = $this->post(route('admin.gallery.store'), [
            'title' => 'Studi Banding Guru 2026',
            'status' => 'draft',
        ]);
        $duplicateResponse->assertRedirect(route('admin.gallery.index'));

        $this->assertDatabaseHas('gallery_albums', [
            'title' => 'Studi Banding Guru 2026',
            'slug' => 'studi-banding-guru-2026-1',
            'status' => 'draft',
        ]);
    }

    /**
     * Test Album updates and photo item synchronization.
     */
    public function test_album_updates_and_photo_syncing(): void
    {
        $this->actingAs($this->superAdmin);

        // Create initial album
        $album = GalleryAlbum::create([
            'title' => 'Wisuda Angkatan 2026',
            'slug' => 'wisuda-angkatan-2026',
            'status' => 'draft',
            'created_by' => $this->superAdmin->id,
        ]);

        // Update data with synced photos
        $updateData = [
            'title' => 'Wisuda Angkatan 2026 (Revisi)',
            'slug' => 'wisuda-angkatan-2026',
            'description' => 'Foto wisuda kelulusan siswa.',
            'cover_media_id' => $this->dummyMedia1->id,
            'status' => 'published',
            'items' => [
                [
                    'media_id' => $this->dummyMedia1->id,
                    'caption' => 'Pemberian piagam penghargaan',
                    'sort_order' => 1,
                ],
                [
                    'media_id' => $this->dummyMedia2->id,
                    'caption' => 'Foto bersama angkatan',
                    'sort_order' => 0, // Should be first due to lower sort order
                ]
            ]
        ];

        $response = $this->put(route('admin.gallery.update', $album->id), $updateData);
        $response->assertRedirect(route('admin.gallery.index'));

        // Verify album updated
        $this->assertDatabaseHas('gallery_albums', [
            'id' => $album->id,
            'title' => 'Wisuda Angkatan 2026 (Revisi)',
            'status' => 'published',
        ]);

        // Verify items synchronized
        $this->assertDatabaseHas('gallery_album_items', [
            'album_id' => $album->id,
            'media_id' => $this->dummyMedia1->id,
            'caption' => 'Pemberian piagam penghargaan',
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('gallery_album_items', [
            'album_id' => $album->id,
            'media_id' => $this->dummyMedia2->id,
            'caption' => 'Foto bersama angkatan',
            'sort_order' => 0,
        ]);

        // Test sorting order of retrieved items (should be dummyMedia2 then dummyMedia1)
        $album->refresh();
        $items = $album->items;
        $this->assertCount(2, $items);
        $this->assertEquals($this->dummyMedia2->id, $items[0]->media_id);
        $this->assertEquals($this->dummyMedia1->id, $items[1]->media_id);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'gallery.album.update',
            'user_id' => $this->superAdmin->id,
            'auditable_id' => $album->id,
        ]);
    }

    /**
     * Test Album soft deletion.
     */
    public function test_album_soft_deletion(): void
    {
        $this->actingAs($this->superAdmin);

        $album = GalleryAlbum::create([
            'title' => 'Pameran Karya Seni',
            'slug' => 'pameran-karya-seni',
            'status' => 'published',
            'created_by' => $this->superAdmin->id,
        ]);

        $response = $this->delete(route('admin.gallery.destroy', $album->id));
        $response->assertRedirect(route('admin.gallery.index'));

        $this->assertSoftDeleted('gallery_albums', ['id' => $album->id]);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'gallery.album.delete',
            'user_id' => $this->superAdmin->id,
            'auditable_id' => $album->id,
        ]);
    }

    /**
     * Test public visitor routes and draft visibility constraints.
     */
    public function test_public_visitor_routes_and_draft_constraints(): void
    {
        // 1. Create one published and one draft album
        $publishedAlbum = GalleryAlbum::create([
            'title' => 'Album Publik',
            'slug' => 'album-publik',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $draftAlbum = GalleryAlbum::create([
            'title' => 'Album Draft',
            'slug' => 'album-draft',
            'status' => 'draft',
        ]);

        // 2. Visit public list
        $response = $this->get(route('public.gallery.index'));
        $response->assertStatus(200);
        $response->assertSee($publishedAlbum->title);
        $response->assertDontSee($draftAlbum->title);

        // 3. Visit published detail
        $response = $this->get(route('public.gallery.show', $publishedAlbum->slug));
        $response->assertStatus(200);
        $response->assertSee($publishedAlbum->title);

        // 4. Visit draft detail (should yield 404)
        $response = $this->get(route('public.gallery.show', $draftAlbum->slug));
        $response->assertStatus(404);
    }
}
