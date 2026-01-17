<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    /**
     * All authenticated users can view attachments.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * All authenticated users can view an attachment.
     */
    public function view(User $user, Attachment $attachment)
    {
        return true;
    }

    /**
     * Only admins and responders can upload attachments.
     */
    public function create(User $user)
    {
        return $user->canWrite();
    }

    /**
     * Only admins can update attachment metadata.
     */
    public function update(User $user, Attachment $attachment)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins and the uploader can delete attachments.
     */
    public function delete(User $user, Attachment $attachment)
    {
        return $user->isAdmin() || $attachment->uploaded_by === $user->id;
    }

    /**
     * Only admins can restore attachments.
     */
    public function restore(User $user, Attachment $attachment)
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can permanently delete attachments.
     */
    public function forceDelete(User $user, Attachment $attachment)
    {
        return $user->isAdmin();
    }
}
