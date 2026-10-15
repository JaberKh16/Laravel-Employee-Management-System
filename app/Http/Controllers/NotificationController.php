<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;



class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Notification::forUser(auth()->id())
            ->active()
            ->latest();

        if ($request->boolean('unread_only')) {
            $query->unread();
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        $perPage = (int) $request->input('per_page', 15);
        $paginator = $query->paginate($perPage);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'unread_count' => Notification::forUser(auth()->id())->active()->unread()->count(),
            ],
        ]);
    }

    public function show(Notification $notification): JsonResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        return response()->json($notification);
    }



    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'count' => Notification::forUser(auth()->id())->active()->unread()->count(),
        ]);
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->markAsRead();
        return response()->json(['ok' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        Notification::forUser(auth()->id())->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return response()->json(['ok' => true]);
    }

    public function dismiss(Notification $notification): JsonResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->dismiss();
        return response()->json(['ok' => true]);
    }

    public function destroy(Notification $notification): JsonResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->delete(); // soft delete (audit trail preserved)
        return response()->json(['ok' => true]);
    }

    public function notifySystemMaintaince(): JsonResponse
    {
        NotificationService::toEveryone(
            type: 'system-maintenance',
            title: 'System Maintenance Scheduled',
            message: 'The system will be down for maintenance on Saturday from 2 AM to 4 AM. Please save your work.',
            options: [
                'category' => 'system',
                'expires_at' => now()->addHours(6),
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function notifyNewEmployee(int $employeeId): JsonResponse
    {
        $employee = Employee::findOrFail($employeeId);

        NotificationService::toRole(
            roleName: 'manager',
            type: 'new-employee',
            title: 'New Employee Added',
            message: "A new employee, {$employee->first_name} {$employee->last_name}, has been added to the system.",
            options: [
                'category' => 'employee',
                'action_url' => route('employees.show', ['employee' => $employee->id]),
                'action_label' => 'View Employee',
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function notifyCustomMessage(int $userId, string $message): JsonResponse
    {
        NotificationService::toUser(
            userId: $userId,
            type: 'custom-message',
            title: 'You have a new message',
            message: $message,
            options: [
                'category' => 'message',
                'priority' => 'high',
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function notifyBulkUsers(array $userIds, string $message): JsonResponse
    {
        NotificationService::toUsers(
            userIds: $userIds,
            type: 'bulk-notification',
            title: 'Important Update',
            message: $message,
            options: [
                'category' => 'update',
                'priority' => 'normal',
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function notifyAnnouncement(string $announcement): JsonResponse
    {
        NotificationService::toEveryone(
            type: 'announcement',
            title: 'New Announcement',
            message: $announcement,
            options: [
                'category' => 'announcement',
                'priority' => 'high',
            ]
        );

        return response()->json(['ok' => true]);
    }
}

