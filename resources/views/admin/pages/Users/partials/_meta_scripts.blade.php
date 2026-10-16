{{-- ══════════════════════════════════════════════════════════════
     HIDDEN USER DATA
     ══════════════════════════════════════════════════════════════ --}}
<script id="usersData" type="application/json">
{!! json_encode(
    $users->map(function ($u) {
        return [
            'id'         => $u->id,
            'username'   => $u->username,
            'email'      => $u->email,
            'first_name' => optional($u->profile)->first_name,
            'last_name'  => optional($u->profile)->last_name,
            'phone'      => optional($u->profile)->phone ?? null,
            'address'    => optional($u->profile)->address ?? null,
            'birthdate'  => optional($u->profile)->birthdate ?? null,
            'employee'   => $u->employee ? [
                'department' => optional($u->employee->department)->name,
                'hire_date'  => $u->employee->date_hired,
                'status'     => is_object($u->employee->status) ? ($u->employee->status->value ?? null) : $u->employee->status,
            ] : null,
            'edit_url'     => route('users.edit', $u->id),
            'make_emp_url' => route('users.make-employee', $u->id),
        ];
    })->keyBy('id'),
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
) !!}
</script>