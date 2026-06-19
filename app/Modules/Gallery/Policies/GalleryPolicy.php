<?php

declare(strict_types=1);

namespace App\Modules\Gallery\Policies;

use App\Models\User;

class GalleryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('gallery.manage');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('gallery.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('gallery.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('gallery.manage');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('gallery.manage');
    }
}
