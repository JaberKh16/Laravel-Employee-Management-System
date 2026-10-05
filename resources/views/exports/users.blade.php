<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Users Export — {{ now()->format('Y-m-d') }}</title>
    <style>
        /* ==========================================================
           RESET & BASE
           ========================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: DejaVu Sans, sans-serif;
        }

        @page {
            margin: 140px 40px 70px 40px;   /* top, right, bottom, left */
        }

        body {
            font-size: 9px;
            color: #1f2937;
            line-height: 1.45;
        }

        /* ==========================================================
           HEADER (fixed on every page, centered brand)
           ========================================================== */
        header {
            position: fixed;
            top: -110px;
            left: 0;
            right: 0;
            height: 100px;
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 12px;
        }

        header .brand-name {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.1;
        }

        header .brand-sub {
            font-size: 9px;
            color: #6b7280;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        header .divider {
            width: 60px;
            height: 2px;
            background: #4f46e5;
            margin: 10px auto 8px;
        }

        header .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 0.5px;
        }

        header .report-sub {
            font-size: 8px;
            color: #9ca3af;
            margin-top: 2px;
            letter-spacing: 0.3px;
        }

        /* ==========================================================
           FOOTER (fixed on every page)
           ========================================================== */
        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
            height: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
            font-size: 7.5px;
            color: #9ca3af;
        }

        footer .left {
            float: left;
        }

        footer .right {
            float: right;
            text-align: right;
        }

        footer .page-number:after {
            content: counter(page);
        }

        footer .page-total:after {
            content: counter(pages);
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* ==========================================================
           SUMMARY STATS BAR
           ========================================================== */
        .summary {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #4f46e5;
            padding: 12px 16px;
            margin-bottom: 14px;
            font-size: 9px;
            color: #374151;
            border-radius: 4px;
        }

        .summary .title {
            font-size: 10px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }

        .summary .details {
            font-size: 8.5px;
            color: #6b7280;
            line-height: 1.6;
        }

        .summary .details strong {
            color: #4f46e5;
            font-weight: bold;
        }

        .summary .chip {
            display: inline-block;
            background: #eef2ff;
            color: #4338ca;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            margin-right: 4px;
            font-weight: bold;
        }

        /* ==========================================================
           DATA TABLE
           ========================================================== */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.data thead {
            display: table-header-group;
        }

        table.data thead th {
            background: #1f2937;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            text-align: left;
            padding: 7px 5px;
            border: 1px solid #1f2937;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        table.data tbody td {
            padding: 6px 5px;
            border: 1px solid #e5e7eb;
            font-size: 8.5px;
            color: #1f2937;
            vertical-align: middle;
        }

        table.data tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .nowrap {
            white-space: nowrap;
        }

        .text-center {
            text-align: center;
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 8px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .badge-inactive {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        .badge-unknown {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        /* Avatar initials */
        .avatar-cell {
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 10px;
            background: #4f46e5;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            margin-right: 6px;
        }

        .user-cell {
            display: inline-block;
            vertical-align: middle;
        }

        /* Empty state */
        .empty {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
            font-size: 11px;
        }
    </style>
</head>
<body>

{{-- ============================================================
     HEADER — Centered Brand + Report Title (no logo)
     ============================================================ --}}
<header>
    <div class="brand-name">{{ config('app.name', 'Your Company') }}</div>
    <div class="brand-sub">User Management System</div>
    <div class="divider"></div>
    <div class="report-title">Users Report</div>
    <div class="report-sub">Generated on {{ now()->format('l, F d, Y · H:i A') }}</div>
</header>

{{-- ============================================================
     FOOTER — Repeats on every page
     ============================================================ --}}
<footer class="clearfix">
    <div class="left">
        {{ config('app.name', 'Your Company') }} · Confidential · Users Report
    </div>
    <div class="right">
        Page <span class="page-number"></span> of <span class="page-total"></span>
    </div>
</footer>

{{-- ============================================================
     CONTENT
     ============================================================ --}}

{{-- Summary stats bar --}}
<div class="summary">
    <div class="title">Report Summary</div>
    <div class="details">
        <span class="chip">{{ $users->count() }} records</span>
        Total records: <strong>{{ $users->count() }}</strong>

        @if (request('search'))
            · Search: <strong>"{{ request('search') }}"</strong>
        @endif

        @if (request('status'))
            · Status: <strong>{{ ucfirst(request('status')) }}</strong>
        @endif

        @if (request('criteria'))
            · Criterion: <strong>{{ ucfirst(request('criteria')) }}</strong>
        @endif

        @if (request('updated_from') || request('updated_to'))
            · Updated:
            <strong>{{ request('updated_from', '…') }}</strong>
            →
            <strong>{{ request('updated_to', '…') }}</strong>
        @endif

        <br>
        Generated at <strong>{{ now()->format('Y-m-d H:i:s') }}</strong>
        by {{ auth()->user()->name ?? auth()->user()->username ?? 'System' }}
    </div>
</div>

{{-- Data table --}}
<table class="data">
    <thead>
        <tr>
            <th style="width: 3%;"  class="text-center">#</th>
            <th style="width: 14%;">User</th>
            <th style="width: 16%;">Email</th>
            <th style="width: 8%;"  class="text-center">Status</th>
            <th style="width: 12%;">Full Name</th>
            <th style="width: 9%;">Phone</th>
            <th style="width: 8%;">Birthdate</th>
            <th style="width: 6%;">Gender</th>
            <th style="width: 9%;">City</th>
            <th style="width: 9%;">Country</th>
            <th style="width: 10%;">Updated</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $i => $user)
            @php
                $p = $user->profile;

                $status = $user->status instanceof \App\Http\Enums\ActiveStatus
                    ? $user->status->label()
                    : (string) ($user->status ?? 'Unknown');

                $badgeClass = match (strtolower($status)) {
                    'active'   => 'badge-active',
                    'inactive' => 'badge-inactive',
                    default    => 'badge-unknown',
                };

                $fullName = optional($p)->full_name
                    ?? trim(($user->username ?? ''));

                $initials = strtoupper(mb_substr($user->username ?? 'U', 0, 2));
            @endphp
            <tr>
                <td class="text-center nowrap">{{ $i + 1 }}</td>

                <td>
                    <span class="avatar-cell">{{ $initials }}</span>
                    <span class="user-cell">{{ $user->username }}</span>
                </td>

                <td>{{ $user->email }}</td>

                <td class="text-center">
                    <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                </td>

                <td>{{ $fullName }}</td>

                <td class="nowrap">{{ optional($p)->phone ?: '—' }}</td>

                <td class="nowrap">
                    {{ optional(optional($p)->birthdate)->format('Y-m-d') ?: '—' }}
                </td>

                <td>{{ ucfirst(optional($p)->gender ?? '—') }}</td>

                <td>{{ optional(optional($p)->city)->name ?: '—' }}</td>

                <td>{{ optional(optional($p)->country)->name ?: '—' }}</td>

                <td class="nowrap">
                    {{ optional($user->updated_at)->format('Y-m-d H:i') ?: '—' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="empty">No users found for the current filters.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>