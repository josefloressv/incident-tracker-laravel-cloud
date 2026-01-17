<?php

namespace App\Policies;

use App\Models\IncidentUpdate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncidentUpdatePolicy
{
    use HandlesAuthorization;

    /**
     * All authenticated users can view incident updates.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * All authenticated users can view an incident update.
     */
    public function view(User $user, IncidentUpdate $incidentUpdate)
    {
        return true;
    }

    /**
     * Only admins and responders can create incident updates.
     */
    public function create(User $user)
    {
        return $user->canWrite();
    }

    /**
     * Only admins and responders can update incident updates.
     */
    public function update(User $user, IncidentUpdate $incidentUpdate)
    {
        return $user->canWrite();
    }

    /**
     * Only admins can delete incident updates.
     */
    public function delete(User $user, IncidentUpdate $incidentUpdate)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can restore incident updates.
     */
    public function restore(User $user, IncidentUpdate $incidentUpdate)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can permanently delete incident updates.
     */
    public function forceDelete(User $user, IncidentUpdate $incidentUpdate)
    {
        return $user->isAdmin();
    }
}
