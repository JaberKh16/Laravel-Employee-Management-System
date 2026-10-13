<?php

namespace App\Http\Controllers;

use App\Models\Notification;
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
}

