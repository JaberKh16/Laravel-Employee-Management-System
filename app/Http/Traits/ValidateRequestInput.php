<?php

namespace App\Http\Traits;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ValidateRequestInput
{
    /**
     * Live check: username availability.
     */
    public function checkUsername(Request $request): JsonResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50'],
            'ignore' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $exists = User::where('username', $request->username)
            ->when($request->filled('ignore'), fn($q) => $q->where('id', '!=', $request->ignore))
            ->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Username is already taken.' : 'Username is available.',
        ]);
    }

    /**
     * Live check: email availability.
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
            'ignore' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $exists = User::where('email', $request->email)
            ->when($request->filled('ignore'), fn($q) => $q->where('id', '!=', $request->ignore))
            ->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Email is already registered.' : 'Email is available.',
        ]);
    }

    /**
     * Live check: phone (from profiles table).
     */
    public function checkPhone(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:7', 'max:20'],
            'ignore_user' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $exists = Profile::where('phone', $request->phone)
            ->when($request->filled('ignore_user'), fn($q) => $q->where('user_id', '!=', $request->ignore_user))
            ->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Phone number is already in use.' : 'Phone number is available.',
        ]);
    }

    /**
     * Live check: password strength.
     */
    public function checkPassword(Request $request): JsonResponse
    {
        $password = (string) $request->input('password', '');

        $score = 0;
        if (strlen($password) >= 8)
            $score++;
        if (preg_match('/[A-Z]/', $password))
            $score++;
        if (preg_match('/[a-z]/', $password))
            $score++;
        if (preg_match('/[0-9]/', $password))
            $score++;
        if (preg_match('/[^A-Za-z0-9]/', $password))
            $score++;

        $labels = [
            0 => 'Very weak',
            1 => 'Weak',
            2 => 'Fair',
            3 => 'Good',
            4 => 'Strong',
            5 => 'Very strong',
        ];

        return response()->json([
            'valid' => $score >= 3,
            'score' => $score,
            'max' => 5,
            'label' => $labels[$score],
            'message' => $score >= 3
                ? 'Password is acceptable.'
                : 'Add uppercase letters, numbers, or symbols.',
        ]);
    }
}