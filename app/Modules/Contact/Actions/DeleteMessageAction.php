<?php

declare(strict_types=1);

namespace App\Modules\Contact\Actions;

use App\Modules\Contact\Models\ContactMessage;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class DeleteMessageAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function execute(ContactMessage $message): void
    {
        DB::transaction(function () use ($message) {
            $oldValues = [
                'name' => $message->name,
                'email' => $message->email,
                'subject' => $message->subject,
                'status' => $message->status,
            ];

            $message->delete();

            // Log Audit
            $this->systemService->logAudit('contact.message.delete', $message, $oldValues, null);
        });
    }
}
