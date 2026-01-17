<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Users can view their own subscriptions.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Users can only view their own subscriptions.
     */
    public function view(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id || $user->isAdmin();
    }

    /**
     * All authenticated users can create subscriptions.
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Users can only update their own subscriptions.
     */
    public function update(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id || $user->isAdmin();
    }

    /**
     * Users can only delete their own subscriptions (unsubscribe).
     */
    public function delete(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id || $user->isAdmin();
    }

    /**
     * Only admins can restore subscriptions.
     */
    public function restore(User $user, Subscription $subscription)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can permanently delete subscriptions.
     */
    public function forceDelete(User $user, Subscription $subscription)
    {
        return $user->isAdmin();
    }
}
