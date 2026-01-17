<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncidentPolicy
{
    use HandlesAuthorization;

    /**
     * All authenticated users can view the list of incidents.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * All authenticated users can view an incident.
     */
    public function view(User $user, Incident $incident)
    {
        return true;
    }

    /**
     * Only admins and responders can create incidents.
     */
    public function create(User $user)
    {
        return $user->canWrite();
    }

    /**
     * Only admins and responders can update incidents.
     */
    public function update(User $user, Incident $incident)
    {
        return $user->canWrite();
    }

    /**
     * Only admins can delete incidents.
     */
    public function delete(User $user, Incident $incident)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can restore incidents.
     */
    public function restore(User $user, Incident $incident)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can permanently delete incidents.
     */
    public function forceDelete(User $user, Incident $incident)
    {
        return $user->isAdmin();
    }
}
