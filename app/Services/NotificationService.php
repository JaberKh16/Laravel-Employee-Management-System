<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NotificationService
{
    /* =========================================================
       CRUD / request-based store (used by admin API)
       ========================================================= */
    public function storeNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'type'         => 'required|string|max:255',
            'title'        => 'required|string|max:255',
            'message'      => 'nullable|string',
            'category'     => 'nullable|string|max:255',
            'icon'         => 'nullable|string|max:255',
            'action_url'   => 'nullable|url|max:2048',
            'action_label' => 'nullable|string|max:255',
            'priority'     => 'nullable|in:low,normal,high',
            'data'         => 'nullable|array',
            'expires_at'   => 'nullable|date',
        ]);

        $notification = Notification::create($validated);

        return response()->json($notification, 201);
    }

    /* =========================================================
       Core dispatchers
       ========================================================= */
    public static function toUser(
        int $userId,
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Notification {
        return Notification::create(array_merge([
            'user_id'      => $userId,
            'audience'     => 'user',
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'category'     => $options['category'] ?? 'general',
            'icon'         => $options['icon'] ?? null,
            'action_url'   => $options['action_url'] ?? null,
            'action_label' => $options['action_label'] ?? null,
            'priority'     => $options['priority'] ?? 'normal',
            'data'         => $options['data'] ?? null,
            'expires_at'   => $options['expires_at'] ?? null,
        ], self::relatedPayload($options)));
    }

    public static function toUsers(
        array $userIds,
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Collection {
        return collect($userIds)->map(
            fn ($id) => self::toUser($id, $type, $title, $message, $options)
        );
    }

    public static function toRole(
        string $roleName,
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Collection {
        $userIds = User::query()->role($roleName)->pluck('id')->all();

        return self::toUsers($userIds, $type, $title, $message, $options);
    }

    public static function toEveryone(
        string $type,
        string $title,
        ?string $message = null,
        array $options = []
    ): Collection {
        return self::toUsers(
            User::query()->pluck('id')->all(),
            $type, $title, $message, $options
        );
    }

    /* =========================================================
       Grouped (dedupe) notification
       ========================================================= */
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
                'message'    => $message ?? $existing->message,
                'updated_at' => now(),
            ]);
            return $existing;
        }

        return tap(
            self::toUser($userId, $type, $title, $message, array_merge($options, [
                'data' => array_merge($options['data'] ?? [], ['group_key' => $groupKey]),
            ])),
            fn (Notification $n) => $n->update(['group_key' => $groupKey])
        );
    }

    /* =========================================================
       Bulk operation helpers (used by controller `bulk()`)
       ========================================================= */
    public static function bulkMarkRead(int $userId, array $ids): int
    {
        return Notification::forUser($userId)
            ->whereIn('id', $ids)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public static function bulkDismiss(int $userId, array $ids): int
    {
        return Notification::forUser($userId)
            ->whereIn('id', $ids)
            ->update(['is_dismissed' => true, 'dismissed_at' => now()]);
    }

    /* =========================================================
       Pre-built notifications (moved OUT of controller)
       ========================================================= */
    public static function systemMaintenance(): void
    {
        self::toEveryone(
            type: 'system-maintenance',
            title: 'System Maintenance Scheduled',
            message: 'The system will be down for maintenance on Saturday from 2 AM to 4 AM. Please save your work.',
            options: [
                'category'   => 'system',
                'expires_at' => now()->addHours(6),
            ]
        );
    }

    public static function newEmployee(Employee $employee): void
    {
        self::toRole(
            roleName: 'manager',
            type: 'new-employee',
            title: 'New Employee Added',
            message: "A new employee, {$employee->first_name} {$employee->last_name}, has been added to the system.",
            options: [
                'category'     => 'employee',
                'action_url'   => route('employees.show', ['employee' => $employee->id]),
                'action_label' => 'View Employee',
            ]
        );
    }

    public static function customMessage(int $userId, string $message): void
    {
        self::toUser(
            userId: $userId,
            type: 'custom-message',
            title: 'You have a new message',
            message: $message,
            options: ['category' => 'message', 'priority' => 'high']
        );
    }

    public static function bulkUsers(array $userIds, string $message): void
    {
        self::toUsers(
            userIds: $userIds,
            type: 'bulk-notification',
            title: 'Important Update',
            message: $message,
            options: ['category' => 'update', 'priority' => 'normal']
        );
    }

    public static function announcement(string $announcement): void
    {
        self::toEveryone(
            type: 'announcement',
            title: 'New Announcement',
            message: $announcement,
            options: ['category' => 'announcement', 'priority' => 'high']
        );
    }

    /* =========================================================
       Internal
       ========================================================= */
    protected static function relatedPayload(array $options): array
    {
        return [
            'related_type' => $options['related_type'] ?? null,
            'related_id'   => $options['related_id'] ?? null,
        ];
    }
}