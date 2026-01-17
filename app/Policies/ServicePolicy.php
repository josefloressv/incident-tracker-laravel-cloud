<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * All authenticated users can view the list of services.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * All authenticated users can view a service.
     */
    public function view(User $user, Service $service)
    {
        return true;
    }

    /**
     * Only admins and responders can create services.
     */
    public function create(User $user)
    {
        return $user->canWrite();
    }

    /**
     * Only admins and responders can update services.
     */
    public function update(User $user, Service $service)
    {
        return $user->canWrite();
    }

    /**
     * Only admins can delete services.
     */
    public function delete(User $user, Service $service)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can restore services.
     */
    public function restore(User $user, Service $service)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can permanently delete services.
     */
    public function forceDelete(User $user, Service $service)
    {
        return $user->isAdmin();
    }
}
