<?php

namespace App\Http\Helpers\Templates;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Throwable;

class UserTemplates
{
    /* ══════════════════════════════════════════════════════════════
       BUILD FILTERED QUERY
       Used by: index, export
       ══════════════════════════════════════════════════════════════ */
    public function buildFilteredQuery(Request $request)
    {
        $query = User::with(['profile', 'roles', 'employee.department']);

        // ── Global search ──────────────────────────────────────────
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($p) use ($search) {
                        $p->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // ── Criteria: Name ─────────────────────────────────────────
        if ($name = $request->input('name')) {
            $query->where(function ($q) use ($name) {
                $q->where('username', 'like', "%{$name}%")
                    ->orWhereHas('profile', function ($p) use ($name) {
                        $p->where('first_name', 'like', "%{$name}%")
                            ->orWhere('last_name', 'like', "%{$name}%");
                    });
            });
        }

        // ── Criteria: Email ────────────────────────────────────────
        if ($email = $request->input('email')) {
            $query->where('email', 'like', "%{$email}%");
        }

        // ── Criteria: Date (created_at) ────────────────────────────
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // ── Criteria: DOB ──────────────────────────────────────────
        if ($dob = $request->input('dob')) {
            $query->whereHas('profile', fn($p) => $p->whereDate('birthdate', $dob));
        }

        // ── Criteria: Status ───────────────────────────────────────
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // ── Criteria: Timestamp range ──────────────────────────────
        if ($from = $request->input('updated_from')) {
            $query->where('updated_at', '>=', $from);
        }
        if ($to = $request->input('updated_to')) {
            $query->where('updated_at', '<=', $to);
        }

        return $query->latest('updated_at');
    }

    /* ══════════════════════════════════════════════════════════════
       COLUMNS (for the table header)
       ══════════════════════════════════════════════════════════════ */
    public function columnsToSelect(): array
    {
        return [
            'index' => '#',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'status' => 'Status',
            'role' => 'Role',
            'updated_at' => 'Last Modified',
            'actions' => 'Actions',
        ];
    }

    /* ══════════════════════════════════════════════════════════════
       COLUMNS (for export — hides #/actions)
       ══════════════════════════════════════════════════════════════ */
    public function columnsToExport(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'status' => 'Status',
            'role' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Last Modified',
        ];
    }

    /* ══════════════════════════════════════════════════════════════
       DOWNLOAD OPTIONS (for dropdown)
       ══════════════════════════════════════════════════════════════ */
    public function downloadOptionsForExport(): array
    {
        return [
            ['format' => 'csv', 'label' => 'CSV', 'icon' => 'ri-file-excel-2-line', 'color' => 'text-emerald-600'],
            ['format' => 'xlsx', 'label' => 'Excel (XLSX)', 'icon' => 'ri-file-excel-2-line', 'color' => 'text-green-600'],
            ['format' => 'pdf', 'label' => 'PDF', 'icon' => 'ri-file-pdf-2-line', 'color' => 'text-red-600'],
            ['format' => 'json', 'label' => 'JSON', 'icon' => 'ri-file-code-line', 'color' => 'text-amber-600'],
            ['format' => 'print', 'label' => 'Print', 'icon' => 'ri-printer-line', 'color' => 'text-blue-600'],
        ];
    }

    /* ══════════════════════════════════════════════════════════════
       ACTION BUTTONS (per user row)
       ══════════════════════════════════════════════════════════════ */
    // public function actionButtons(User $user): array
    // {
    //     return [
    //         'view_profile' => [
    //             'route' => route('users.profile.show', $user->id),
    //             'icon' => 'ri-eye-line',
    //             'label' => 'View Profile',
    //             'color' => 'text-slate-600',
    //         ],
    //         'make_employee' => [
    //             'route' => route('users.make-employee', $user->id),
    //             'icon' => 'ri-briefcase-4-line',
    //             'label' => 'Make Employee',
    //             'color' => 'text-emerald-600',
    //             'confirm' => true,
    //         ],
    //         'edit' => [
    //             'route' => route('users.edit', $user->id),
    //             'icon' => 'ri-edit-line',
    //             'label' => 'Edit',
    //             'color' => 'text-indigo-600',
    //         ],
    //         'delete' => [
    //             'route' => route('users.destroy', $user->id),
    //             'icon' => 'ri-delete-bin-6-line',
    //             'label' => 'Delete',
    //             'color' => 'text-red-600',
    //             'confirm' => true,
    //         ],
    //     ];
    // }


    public function actionButtons(User $user): array
    {
        // Build routes safely — if any route name is missing, we skip it
        $viewProfileRoute = $this->safeRoute('users.profile.show', $user->id);
        $makeEmployeeRoute = $this->safeRoute('users.make-employee', $user->id);
        $editRoute = $this->safeRoute('users.edit', $user->id);
        $deleteRoute = $this->safeRoute('users.destroy', $user->id);

        return [
            'view_profile' => $viewProfileRoute ? [
                'route' => $viewProfileRoute,
                'icon' => 'ri-eye-line',
                'label' => 'View Profile',
                'color' => 'text-slate-600',
            ] : null,

            'make_employee' => $makeEmployeeRoute ? [
                'route' => $makeEmployeeRoute,
                'icon' => 'ri-briefcase-4-line',
                'label' => 'Make Employee',
                'color' => 'text-emerald-600',
                'confirm' => true,
            ] : null,

            'edit' => $editRoute ? [
                'route' => $editRoute,
                'icon' => 'ri-edit-line',
                'label' => 'Edit',
                'color' => 'text-indigo-600',
            ] : null,

            'delete' => $deleteRoute ? [
                'route' => $deleteRoute,
                'icon' => 'ri-delete-bin-6-line',
                'label' => 'Delete',
                'color' => 'text-red-600',
                'confirm' => true,
            ] : null,
        ];
    }

    /**
     * Safely build a route URL, returning null if the route doesn't exist.
     */
    private function safeRoute(string $name, mixed $params = null): ?string
    {
        try {
            return Route::has($name)
                ? route($name, $params)
                : null;
        } catch (Throwable $e) {
            return null;
        }
    }
}