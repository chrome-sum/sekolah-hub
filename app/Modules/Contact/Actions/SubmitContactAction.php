<?php

declare(strict_types=1);

namespace App\Modules\Contact\Actions;

use App\Modules\Contact\Models\ContactMessage;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class SubmitContactAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(array $data): ContactMessage
    {
        return DB::transaction(function () use ($data) {
            $message = ContactMessage::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'status' => 'unread',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Log Audit
            $this->systemService->logAudit('contact.message.submit', $message, null, [
                'name' => $message->name,
                'email' => $message->email,
                'subject' => $message->subject,
            ]);

            return $message;
        });
    }
}
