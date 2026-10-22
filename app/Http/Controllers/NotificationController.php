<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /* =========================================================
       JSON feed (bell dropdown)
       ========================================================= */
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->query($request)
            ->paginate((int) $request->input('per_page', 15));

        $items = collect($paginator->items())->map(fn (Notification $n) => [
            'id'           => $n->id,
            'type'         => $n->type,
            'title'        => $n->title,
            'message'      => $n->message,
            'category'     => $n->category ?? 'general',
            'action_url'   => $n->action_url,
            'action_label' => $n->action_label,
            'is_read'      => (bool) $n->is_read,
            'group_count'  => (int) $n->group_count,
            'priority'     => $n->priority,
            'time_ago'     => $n->time_ago,
            'created_at'   => $n->created_at?->toIso8601String(),
        ]);

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'unread_count' => $this->unreadCountValue(), // topbar reads this
            ],
        ]);
    }

    /* =========================================================
       HTML page (full-page table view)
       ========================================================= */
    public function list(Request $request): View
    {
        $notifications = $this->query($request)
            ->paginate((int) $request->input('per_page', 15))
            ->withQueryString();

        // Shape the Blade expects for initial render
        $meta = [
            'total'        => $notifications->total(),
            'unread_count' => $this->unreadCountValue(),
            'read_count'   => $this->readCountValue(),
        ];

        return view('admin.pages.notifications.list', [
            'notifications' => $notifications,
            'meta'          => $meta,
            'search'        => $request->input('search', ''),
            'category'      => $request->input('category', ''),
            'unreadOnly'    => $request->boolean('unread_only'),
            'statusFilter'  => $request->input('status', ''),
        ]);
    }

    /* =========================================================
       Single notification
       ========================================================= */
    public function show(Notification $notification): JsonResponse
    {
        $this->authorizeOwnership($notification);

        return response()->json($notification);
    }

    /* =========================================================
       Counts — for JS (topbar + table cards)
       ========================================================= */
    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'count' => $this->unreadCountValue(),
            'read'  => $this->readCountValue(),
            'total' => $this->totalCountValue(),
        ]);
    }

    /* =========================================================
       Read / dismiss / delete
       ========================================================= */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $this->authorizeOwnership($notification);
        $notification->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        Notification::forUser(auth()->id())
            ->active()
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function dismiss(Notification $notification): JsonResponse
    {
        $this->authorizeOwnership($notification);
        $notification->dismiss();

        return response()->json(['ok' => true]);
    }

    public function destroy(Notification $notification): JsonResponse
    {
        $this->authorizeOwnership($notification);
        $notification->delete();

        return response()->json(['ok' => true]);
    }

    /* =========================================================
       Bulk Operation
       ========================================================= */
    public function bulkOperation(Request $request, string $action): JsonResponse
    {
        $data = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $userId = auth()->id();

        $count = match ($action) {
            'read'    => NotificationService::bulkMarkRead($userId, $data['ids']),
            'dismiss' => NotificationService::bulkDismiss($userId, $data['ids']),
            default   => 0,
        };

        return response()->json(['ok' => true, 'updated' => $count]);
    }

    /* =========================================================
       Admin actions — thin controllers, logic in the service
       ========================================================= */
    public function notifySystemMaintaince(): JsonResponse
    {
        NotificationService::systemMaintenance();
        return response()->json(['ok' => true]);
    }

    public function notifyNewEmployee(int $employeeId): JsonResponse
    {
        $employee = Employee::findOrFail($employeeId);
        NotificationService::newEmployee($employee);
        return response()->json(['ok' => true]);
    }

    public function notifyCustomMessage(int $userId, string $message): JsonResponse
    {
        NotificationService::customMessage($userId, $message);
        return response()->json(['ok' => true]);
    }

    public function notifyBulkUsers(array $userIds, string $message): JsonResponse
    {
        NotificationService::bulkUsers($userIds, $message);
        return response()->json(['ok' => true]);
    }

    public function notifyAnnouncement(string $announcement): JsonResponse
    {
        NotificationService::announcement($announcement);
        return response()->json(['ok' => true]);
    }

    /* =========================================================
       Internal
       ========================================================= */
    private function query(Request $request)
    {
        return Notification::forUser(auth()->id())
            ->active()
            ->when($request->boolean('unread_only'), fn ($q) => $q->unread())
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->when($request->filled('status'), function ($q) use ($request) {
                match ($request->string('status')->toString()) {
                    'unread' => $q->unread(),
                    'read'   => $q->whereNotNull('read_at'),
                    default  => null,
                };
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%' . $request->string('search')->toString() . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('message', 'like', $term);
                });
            })
            ->latest();
    }

    private function baseQuery()
    {
        return Notification::forUser(auth()->id())->active();
    }

    private function unreadCountValue(): int
    {
        return (clone $this->baseQuery())->unread()->count();
    }

    private function readCountValue(): int
    {
        return (clone $this->baseQuery())->whereNotNull('read_at')->count();
    }

    private function totalCountValue(): int
    {
        return $this->baseQuery()->count();
    }

    private function authorizeOwnership(Notification $notification): void
    {
        abort_unless($notification->user_id === auth()->id(), 403);
    }
}