<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'parent.profile',
        ]);

        return response()->json([
            'data' => $this->transformProfile($user),
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE PROFILE (from modal edit mode)
    // PUT /admin/users/{user}/profile/modal
    // ═══════════════════════════════════════════════════════════
    public function updateProfile(Request $request, User $user): JsonResponse
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
            'status' => is_object($user->status)
                ? strtolower($user->status->label())
                : strtolower((string) ($user->status ?? 'unknown')),

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
                'city' => ['name' => $p->city?->name],
                'state' => ['name' => $p->state?->name],
                'country' => ['name' => $p->country?->name],
            ] : null,

            // ── Employee ───────────────────────────────────
            'employee' => $user->employee ? [
                'employee_id' => $user->employee->employee_id,
                'designation' => $user->employee->designation,
                'hire_date' => optional($user->employee->hire_date)->format('M d, Y'),
                'status' => is_object($user->employee->status)
                    ? ($user->employee->status->label() ?? $user->employee->status->value)
                    : $user->employee->status,
                'department' => ['name' => $user->employee->department?->name],
            ] : null,

            // ── Parent ─────────────────────────────────────
            'parent' => $user->parent ? [
                'full_name' => $user->parent->profile?->full_name ?? $user->parent->username,
            ] : null,
        ];
    }
}
