<?php

declare(strict_types=1);

namespace Tests\Feature\Contact;

use App\Models\User;
use App\Modules\Contact\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ContactTest extends TestCase
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

        // Reset rate limits before each test
        RateLimiter::clear('contact-submit|127.0.0.1');
    }

    /**
     * Test admin authorization for message inbox.
     */
    public function test_only_authorized_users_can_access_inbox(): void
    {
        // Guests cannot view inbox
        $response = $this->actingAs($this->guestUser)->get(route('admin.contacts.index'));
        $response->assertStatus(403);

        // Super Admin can view inbox
        $response = $this->actingAs($this->superAdmin)->get(route('admin.contacts.index'));
        $response->assertStatus(200);
    }

    /**
     * Test contact submission and metadata storage.
     */
    public function test_public_contact_submission_and_audit_logging(): void
    {
        // Visit public form
        $response = $this->get(route('public.contact.show'));
        $response->assertStatus(200);

        // Submit message
        $payload = [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'phone' => '081234567890',
            'subject' => 'Tanya Jadwal Sekolah',
            'message' => 'Halo, saya ingin bertanya jadwal masuk sekolah.',
        ];

        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_USER_AGENT' => 'Mozilla/TestAgent',
        ])->post(route('public.contact.submit'), $payload);

        $response->assertRedirect(route('public.contact.show'));
        $response->assertSessionHas('success');

        // Verify Database
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'phone' => '081234567890',
            'subject' => 'Tanya Jadwal Sekolah',
            'status' => 'unread',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/TestAgent',
        ]);

        $message = ContactMessage::where('email', 'budi.santoso@gmail.com')->first();
        $this->assertNotNull($message);

        // Verify Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'contact.message.submit',
            'auditable_type' => ContactMessage::class,
            'auditable_id' => $message->id,
        ]);
    }

    /**
     * Test rate limiting on contact form submissions.
     */
    public function test_contact_form_submissions_are_rate_limited(): void
    {
        $payload = [
            'name' => 'Spammer',
            'email' => 'spam@bot.com',
            'subject' => 'Spam Subject',
            'message' => 'Spam content',
        ];

        // First 5 requests should pass
        for ($i = 0; $i < 5; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
                ->post(route('public.contact.submit'), $payload);
            $response->assertRedirect(route('public.contact.show'));
        }

        // 6th request should fail with ValidationException (throttle block)
        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->post(route('public.contact.submit'), $payload);
        
        $response->assertSessionHasErrors('email');
        
        $errors = session('errors')->get('email');
        $this->assertTrue(str_contains($errors[0], 'Terlalu banyak mengirim pesan'));
    }

    /**
     * Test admin updating message status.
     */
    public function test_admin_can_update_message_status(): void
    {
        $this->actingAs($this->superAdmin);

        $message = ContactMessage::create([
            'name' => 'Rian',
            'email' => 'rian@gmail.com',
            'subject' => 'Tanya Ujian',
            'message' => 'Halo',
            'status' => 'unread',
        ]);

        // 1. Show message automatically marks it as read
        $response = $this->get(route('admin.contacts.show', $message->id));
        $response->assertStatus(200);

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'read',
        ]);

        // 2. Change status to replied
        $response = $this->put(route('admin.contacts.update_status', $message->id), [
            'status' => 'replied',
        ]);
        $response->assertRedirect(route('admin.contacts.show', $message->id));

        $message->refresh();
        $this->assertEquals('replied', $message->status);
        $this->assertNotNull($message->replied_at);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'contact.message.update_status',
            'user_id' => $this->superAdmin->id,
            'auditable_id' => $message->id,
        ]);
    }

    /**
     * Test admin deleting a message (soft deletes).
     */
    public function test_admin_can_delete_message(): void
    {
        $this->actingAs($this->superAdmin);

        $message = ContactMessage::create([
            'name' => 'Spam Guy',
            'email' => 'spam@guy.com',
            'subject' => 'Viagra Promo',
            'message' => 'Buy cheap meds',
            'status' => 'unread',
        ]);

        $response = $this->delete(route('admin.contacts.destroy', $message->id));
        $response->assertRedirect(route('admin.contacts.index'));

        // Soft deleted
        $this->assertSoftDeleted('contact_messages', ['id' => $message->id]);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'contact.message.delete',
            'user_id' => $this->superAdmin->id,
            'auditable_id' => $message->id,
        ]);
    }
}
