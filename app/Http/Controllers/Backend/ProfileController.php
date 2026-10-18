<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // SHOW PROFILE (for modal)
    // GET /admin/users/{user}/profile/modal
    // ═══════════════════════════════════════════════════════════
    public function showProfile(User $user): JsonResponse
    {
        $user->load([
            'profile.city',
            'profile.state',
            'profile.country',
            'employee.department',
            'employee.jobProfile',
            'parent.profile',
        ]);

        return response()->json([
            'd' => $user,
            'data' => $this->transformProfile($user),
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE PROFILE (from modal edit mode)
    // PUT /admin/users/{user}/profile/modal
    // ═══════════════════════════════════════════════════════════
    public function updateModalProfile(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            // ── User-level ─────────────────────────────────
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            // ── Profile: Personal ──────────────────────────
            'first_name' => ['nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],

            // ── Profile: Contact ───────────────────────────
            'phone' => ['nullable', 'string', 'max:30'],
            'website' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],

            // ── Profile: Location ──────────────────────────
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            // Update user-level field
            $user->update([
                'email' => $validated['email'],
            ]);

            // Extract profile-only fields
            $profileData = collect($validated)->only([
                'first_name',
                'middle_name',
                'last_name',
                'birthdate',
                'gender',
                'phone',
                'website',
                'linkedin',
                'twitter',
                'zip_code',
                'address',
                'country_id',
                'state_id',
                'city_id',
            ])->toArray();

            // Normalize empty strings → null
            $profileData = array_map(function ($v) {
                return $v === '' ? null : $v;
            }, $profileData);

            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $user->profile()->create($profileData);
            }
        });

        $user->refresh()->load([
            'profile.city',
            'profile.state',
            'profile.country',
            'employee.department',
            'parent.profile',
        ]);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => $this->transformProfile($user),
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // SHARED TRANSFORMER
    // ═══════════════════════════════════════════════════════════
    protected function transformProfile(User $user): array
    {
        $p = $user->profile;

        return [
            // ── Identity ───────────────────────────────────
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'full_name' => $p?->full_name ?? $user->username,
            'initials' => $p?->initials ?? strtoupper(substr($user->username ?? 'U', 0, 2)),

            // ── Status (enum-safe) ─────────────────────────
            'status' => $this->resolveStatus($user),

            // ── Timestamps ─────────────────────────────────
            'created_at_human' => $user->created_at?->format('M d, Y · H:i'),
            'updated_at_human' => $user->updated_at?->diffForHumans(),

            // ── Profile ────────────────────────────────────
            'profile' => $p ? [
                'first_name' => $p->first_name,
                'middle_name' => $p->middle_name,
                'last_name' => $p->last_name,
                'birthdate' => $p->birthdate?->format('M d, Y'),
                'birthdate_raw' => $p->birthdate?->format('Y-m-d'),
                'gender' => $p->gender ? ucfirst($p->gender) : null,
                'gender_raw' => $p->gender,
                'age' => $p->birthdate?->age,
                'phone' => $p->phone,
                'website' => $p->website,
                'linkedin' => $p->linkedin,
                'twitter' => $p->twitter,
                'zip_code' => $p->zip_code,
                'address' => $p->address,
                'country_id' => $p->country_id,
                'state_id' => $p->state_id,
                'city_id' => $p->city_id,
                'city' => $p->city ? ['name' => $p->city->name] : null,
                'state' => $p->state ? ['name' => $p->state->name] : null,
                'country' => $p->country ? ['name' => $p->country->name] : null,
            ] : null,

            // ── Employee ───────────────────────────────────
            'employee' => $user->employee ? [
                'employee_id' => $user->employee->employee_id ?? null,
                'designation' => $user->employee->designation ?? null,
                'hire_date' => $user->employee->hire_date
                    ? $user->employee->hire_date->format('M d, Y')
                    : null,
                'status' => $this->resolveEmployeeStatus($user->employee),
                'emp_status' => $this->resolveEmployeeStatus($user->employee),
                'department' => $user->employee->department
                    ? ['name' => $user->employee->department->name]
                    : null,
            ] : null,

            // ── Parent ─────────────────────────────────────
            'parent' => $user->parent ? [
                'full_name' => $user->parent->profile?->full_name ?? $user->parent->username,
            ] : null,
        ];
    }

    /**
     * Resolve user status to a lowercase label string.
     */
    protected function resolveStatus(User $user): string
    {
        $status = $user->status;

        if (is_object($status) && method_exists($status, 'label')) {
            return strtolower($status->label());
        }
        if (is_object($status) && isset($status->value)) {
            return strtolower((string) $status->value);
        }
        return strtolower((string) ($status ?? 'unknown'));
    }

    /**
     * Resolve employee status to a string label.
     */
    protected function resolveEmployeeStatus($employee): ?string
    {
        if (!$employee || !isset($employee->status))
            return null;
        $status = $employee->status;

        if (is_object($status) && method_exists($status, 'label')) {
            return $status->label();
        }
        if (is_object($status) && isset($status->value)) {
            return (string) $status->value;
        }
        return (string) $status;
    }
}