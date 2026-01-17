<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case RESPONDER = 'responder';
    case VIEWER = 'viewer';

    /**
     * Check if the role is admin.
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Check if the role is responder.
     */
    public function isResponder(): bool
    {
        return $this === self::RESPONDER;
    }

    /**
     * Check if the role is viewer.
     */
    public function isViewer(): bool
    {
        return $this === self::VIEWER;
    }

    /**
     * Check if the role can write (admin or responder).
     */
    public function canWrite(): bool
    {
        return $this === self::ADMIN || $this === self::RESPONDER;
    }

    /**
     * Get all role values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get role labels for UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::RESPONDER => 'Responder',
            self::VIEWER => 'Viewer',
        };
    }
}
