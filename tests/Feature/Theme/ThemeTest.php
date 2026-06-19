<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use App\Models\User;
use App\Modules\System\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Seed system and theme configurations
        $this->seed(\App\Modules\System\database\seeders\SystemSeeder::class);
        $this->seed(\App\Modules\Theme\database\seeders\ThemeSeeder::class);

        // 2. Retrieve Super Admin
        $this->superAdmin = User::where('email', 'hasbialaziz67@gmail.com')->first();

        // 3. Create Editor
        $this->editor = User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
        ]);
        $this->editor->assignRole('Editor');
    }

    /**
     * Test public homepage is accessible and renders default theme configurations.
     */
    public function test_public_homepage_renders_active_theme_default_content(): void
    {
        $response = $this->get(route('public.homepage'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang di Sekolah Hub');
        $response->assertSee('Sambutan Kepala Sekolah');
    }

    /**
     * Test only authorized users with settings.manage permission can access theme configurations.
     */
    public function test_only_authorized_users_can_access_theme_admin_dashboard(): void
    {
        // Unauthorized
        $response = $this->actingAs($this->editor)->get(route('admin.themes.index'));
        $response->assertStatus(403);

        // Authorized
        $response = $this->actingAs($this->superAdmin)->get(route('admin.themes.index'));
        $response->assertStatus(200);
        $response->assertViewHas('themes');
        $response->assertViewHas('activeTheme', 'school-classic');
    }

    /**
     * Test admin can activate a new theme.
     */
    public function test_admin_can_activate_theme(): void
    {
        $this->actingAs($this->superAdmin);

        $response = $this->post(route('admin.themes.activate'), [
            'theme' => 'school-classic', // Since we only have one theme, let's re-activate it for testing
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('settings', [
            'key' => 'theme.active',
            'value' => 'school-classic',
        ]);
    }

    /**
     * Test admin can update homepage sections ordering and visibility.
     */
    public function test_admin_can_update_homepage_sections(): void
    {
        $this->actingAs($this->superAdmin);

        $response = $this->post(route('admin.themes.sections.update'), [
            'sections' => ['hero', 'announcement', 'cta'], // Toggle off news, gallery, ppdb, contact
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // Retrieve database value
        $setting = Setting::where('key', 'theme.homepage_sections')->first();
        $this->assertNotNull($setting);
        
        $sections = json_decode($setting->value, true);
        $this->assertEquals(['hero', 'announcement', 'cta'], $sections);

        // Verify public view doesn't render disabled sections
        $response = $this->get(route('public.homepage'));
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang di Sekolah Hub'); // Hero
        $response->assertSee('Sambutan Kepala Sekolah'); // Announcement
        $response->assertDontSee('Berita &amp; Kegiatan Terbaru'); // News (escaped format in view)
    }

    /**
     * Test admin can update theme content configurations.
     */
    public function test_admin_can_update_theme_content_settings(): void
    {
        $this->actingAs($this->superAdmin);

        $response = $this->post(route('admin.themes.settings.update'), [
            'settings' => [
                'hero_title' => 'Sekolah Hub Unggul',
                'hero_subtitle' => 'Motto baru kami.',
                'cta_title' => 'Banner pendaftaran baru.',
                'cta_button_text' => 'Daftar Yuk',
                'cta_button_url' => '/ppdb/daftar',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('settings', [
            'key' => 'theme.school-classic.hero_title',
            'value' => 'Sekolah Hub Unggul',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'theme.school-classic.cta_button_text',
            'value' => 'Daftar Yuk',
        ]);

        // Verify updated values on the homepage
        $response = $this->get(route('public.homepage'));
        $response->assertStatus(200);
        $response->assertSee('Sekolah Hub Unggul');
        $response->assertSee('Daftar Yuk');
    }
}
