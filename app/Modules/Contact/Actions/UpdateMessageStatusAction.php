<?php

declare(strict_types=1);

namespace App\Modules\Contact\Actions;

use App\Modules\Contact\Models\ContactMessage;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class UpdateMessageStatusAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(ContactMessage $message, string $status): ContactMessage
    {
        return DB::transaction(function () use ($message, $status) {
            $oldValues = [
                'status' => $message->status,
                'replied_at' => $message->replied_at ? $message->replied_at->toDateTimeString() : null,
            ];

            $repliedAt = $message->replied_at;
            if ($status === 'replied' && $message->status !== 'replied') {
                $repliedAt = now();
            } elseif ($status !== 'replied') {
                $repliedAt = null;
            }

            $message->update([
                'status' => $status,
                'replied_at' => $repliedAt,
            ]);

            // Log Audit
            $this->systemService->logAudit('contact.message.update_status', $message, $oldValues, [
                'name' => $message->name,
                'subject' => $message->subject,
                'status' => $message->status,
            ]);

            return $message;
        });
    }
}
