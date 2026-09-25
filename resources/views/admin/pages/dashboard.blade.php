@extends('admin.layout.main')

{{-- ============================================================
     STYLES
============================================================ --}}
@push('dashboard_style')
    @once
        <style>
            /* ============================================================
               MODERN PAGE HEADER
               ============================================================ */
            .page-header-modern {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                flex-wrap: wrap;
                padding: 0.25rem 0 1.25rem;
                margin-bottom: 1.5rem;
                border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            }

            /* Left side — title + subtitle */
            .page-header-modern__left {
                min-width: 0;
            }
            .page-header-modern__title {
                font-size: 1.6rem;
                font-weight: 800;
                letter-spacing: -0.02em;
                color: #0f172a;
                margin: 0 0 0.15rem;
                line-height: 1.2;
            }
            .page-header-modern__subtitle {
                font-size: 0.85rem;
                color: #64748b;
                margin: 0;
                line-height: 1.4;
            }

            /* Right side — actions */
            .page-header-modern__actions {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                flex-shrink: 0;
            }

            /* ---------- Generate Report button ---------- */
            .btn-report {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.55rem 1rem 0.55rem 0.55rem;
                background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                color: #ffffff !important;
                font-size: 0.82rem;
                font-weight: 600;
                letter-spacing: 0.01em;
                border-radius: 0.75rem;
                text-decoration: none !important;
                box-shadow:
                    0 8px 20px -8px rgba(99, 102, 241, 0.55),
                    0 2px 6px -2px rgba(15, 23, 42, 0.10);
                transition:
                    transform 0.2s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.2s ease,
                    filter 0.2s ease;
                position: relative;
                overflow: hidden;
            }
            .btn-report:hover {
                transform: translateY(-2px);
                color: #ffffff !important;
                filter: brightness(1.05);
                box-shadow:
                    0 12px 26px -8px rgba(99, 102, 241, 0.65),
                    0 4px 10px -2px rgba(15, 23, 42, 0.15);
            }
            .btn-report:active {
                transform: translateY(0);
                filter: brightness(0.98);
            }
            .btn-report:focus-visible {
                outline: 3px solid rgba(99, 102, 241, 0.35);
                outline-offset: 2px;
            }

            /* Icon chip inside button */
            .btn-report__icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 26px;
                height: 26px;
                background: rgba(255, 255, 255, 0.20);
                border-radius: 0.5rem;
                font-size: 0.72rem;
                transition: transform 0.25s ease, background 0.2s ease;
            }
            .btn-report:hover .btn-report__icon {
                background: rgba(255, 255, 255, 0.30);
                transform: translateY(-1px);
            }
            .btn-report__label {
                white-space: nowrap;
            }

            /* ============================================================
               RESPONSIVE
               ============================================================ */
            @media (max-width: 575.98px) {
                .page-header-modern {
                    padding-bottom: 1rem;
                    margin-bottom: 1.25rem;
                }
                .page-header-modern__title    { font-size: 1.35rem; }
                .page-header-modern__subtitle { font-size: 0.78rem; }

                /* Button becomes full-width on mobile */
                .page-header-modern__actions { width: 100%; }
                .btn-report {
                    width: 100%;
                    justify-content: center;
                    padding: 0.6rem 1rem;
                }
            }

            /* ============================================================
               DARK MODE (optional)
               ============================================================ */
            .dark-mode .page-header-modern {
                border-bottom-color: rgba(255, 255, 255, 0.08);
            }
            .dark-mode .page-header-modern__title    { color: #f1f5f9; }
            .dark-mode .page-header-modern__subtitle { color: #94a3b8; }
        </style>
    @endonce
@endpush


{{-- ============================================================
     CONTENT
     ============================================================ --}}
@section('dashboard_content')

    {{-- Page Header --}}
    <section class="page-header-modern">
        <div class="page-header-modern__left">
            <h1 class="page-header-modern__title">Dashboard</h1>
            <p class="page-header-modern__subtitle">
                Welcome back — here's an overview of your organization
            </p>
        </div>

        <div class="page-header-modern__actions">
            @if (\Route::has('generate.report'))
                <a href="{{ route('generate.report') }}" class="btn-report">
                    <span class="btn-report__icon">
                        <i class="fas fa-download" aria-hidden="true"></i>
                    </span>
                    <span class="btn-report__label">Generate Report</span>
                </a>
            @endif
        </div>
    </section>

    {{-- Stat Cards --}}
    @include('admin.partials._stats-cards')

@endsection


{{-- ============================================================
     SCRIPTS
     ============================================================ --}}
@push('dashboard_script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Animate stat numbers counting up
            document.querySelectorAll('.stat-tile__value, .stat-value').forEach(el => {
                const raw = el.textContent.trim().replace(/,/g, '');
                const target = parseInt(raw, 10);
                if (isNaN(target) || target === 0) return;

                let current = 0;
                const step = Math.max(1, Math.ceil(target / 40));
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    el.textContent = current.toLocaleString();
                }, 20);
            });
        });
    </script>
@endpush