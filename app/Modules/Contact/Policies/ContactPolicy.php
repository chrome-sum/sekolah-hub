<?php

declare(strict_types=1);

namespace App\Modules\Contact\Policies;

use App\Models\User;

class ContactPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('contact.manage');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('contact.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('contact.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('contact.manage');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('contact.manage');
    }
}
