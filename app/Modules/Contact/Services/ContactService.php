<?php

declare(strict_types=1);

namespace App\Modules\Contact\Services;

use App\Modules\Contact\Contracts\ContactServiceInterface;
use App\Modules\Contact\Models\ContactMessage;
use App\Modules\Contact\Actions\SubmitContactAction;
use App\Modules\Contact\Actions\UpdateMessageStatusAction;
use App\Modules\Contact\Actions\DeleteMessageAction;

class ContactService implements ContactServiceInterface
{
    public function __construct(
        protected SubmitContactAction $submitContactAction,
        protected UpdateMessageStatusAction $updateMessageStatusAction,
        protected DeleteMessageAction $deleteMessageAction
    ) {}

    /**
     * @inheritDoc
     */
    public function submitMessage(array $data): ContactMessage
    {
        return $this->submitContactAction->execute($data);
    }

    /**
     * @inheritDoc
     */
    public function updateStatus(ContactMessage $message, string $status): ContactMessage
    {
        return $this->updateMessageStatusAction->execute($message, $status);
    }

    /**
     * @inheritDoc
     */
    public function deleteMessage(ContactMessage $message): void
    {
        $this->deleteMessageAction->execute($message);
    }
}
