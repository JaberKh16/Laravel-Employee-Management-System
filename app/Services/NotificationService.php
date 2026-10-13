<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Send a notification to a single user.
     */
    public static function toUser(
        int $userId,
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Notification {
        return Notification::create(array_merge([
            'user_id' => $userId,
            'audience' => 'user',
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'category' => $options['category'] ?? 'general',
            'icon' => $options['icon'] ?? null,
            'action_url' => $options['action_url'] ?? null,
            'action_label' => $options['action_label'] ?? null,
            'priority' => $options['priority'] ?? 'normal',
            'data' => $options['data'] ?? null,
            'expires_at' => $options['expires_at'] ?? null,
        ], self::relatedPayload($options)));
    }

    /**
     * Send the same notification to multiple users.
     */
    public static function toUsers(
        array $userIds,
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Collection {
        return collect($userIds)->map(
            fn($id) => self::toUser($id, $type, $title, $message, $options)
        );
    }

    /**
     * Send to everyone with a given role (e.g. 'admin', 'manager').
     */
    public static function toRole(
        string $roleName,
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Collection {
        $users = User::role($roleName)->get();
        return self::toUsers($users->pluck('id')->all(), $type, $title, $message, $options);
    }

    /**
     * Broadcast to every user.
     */
    public static function toEveryone(
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Collection {
        return self::toUsers(User::pluck('id')->all(), $type, $title, $message, $options);
    }

    /**
     * Grouped notification — upserts by group_key so we don't spam.
     * Example: "5 new employees added" instead of 5 separate rows.
     */
    public static function grouped(
        int $userId,
        string $groupKey,
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Notification {
        $existing = Notification::forUser($userId)
            ->where('group_key', $groupKey)
            ->where('is_read', false)
            ->where('created_at', '>=', now()->subHours(24))
            ->latest()
            ->first();

        if ($existing) {
            $existing->increment('group_count');
            $existing->update([
                'message' => $message ?? $existing->message,
                'updated_at' => now(),
            ]);
            return $existing;
        }

        return self::toUser($userId, $type, $title, $message, array_merge($options, [
            'data' => array_merge($options['data'] ?? [], ['group_key' => $groupKey]),
        ]))->tap(fn($n) => $n->update(['group_key' => $groupKey]));
    }

    protected static function relatedPayload(array $options): array
    {
        return [
            'related_type' => $options['related_type'] ?? null,
            'related_id' => $options['related_id'] ?? null,
        ];
    }
}