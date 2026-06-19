<?php

declare(strict_types=1);

namespace App\Modules\Contact\Contracts;

use App\Modules\Contact\Models\ContactMessage;

interface ContactServiceInterface
{
    /**
     * Submit a new contact message from public form.
     *
     * @param array $data
     * @return ContactMessage
     */
    public function submitMessage(array $data): ContactMessage;

    /**
     * Update the status of a contact message.
     *
     * @param ContactMessage $message
     * @param string $status
     * @return ContactMessage
     */
    public function updateStatus(ContactMessage $message, string $status): ContactMessage;

    /**
     * Soft delete a contact message.
     *
     * @param ContactMessage $message
     * @return void
     */
    public function deleteMessage(ContactMessage $message): void;
}
