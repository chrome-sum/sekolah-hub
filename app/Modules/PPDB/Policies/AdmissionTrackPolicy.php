<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Policies;

use App\Models\User;

class AdmissionTrackPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ppdb.manage');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('ppdb.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('ppdb.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('ppdb.manage');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('ppdb.manage');
    }
}
