{{-- Stat Cards (Modern Grid) --}}
@php
    $cards = [
        [
            'label' => 'Users',
            'value' => $user_count ?? 0,
            'icon'  => 'fas fa-user',
            'bg'    => 'linear-gradient(135deg, #6366f1 0%, #4338ca 100%)',
            'soft'  => 'rgba(99, 102, 241, 0.15)',
            'trend' => '+12.5%',
            'up'    => true,
            'link'  => \Route::has('users.index') ? route('users.index') : '#',
        ],
        [
            'label' => 'Countries',
            'value' => $country_count ?? 0,
            'icon'  => 'fas fa-globe-asia',
            'bg'    => 'linear-gradient(135deg, #10b981 0%, #047857 100%)',
            'soft'  => 'rgba(16, 185, 129, 0.15)',
            'trend' => '+4.2%',
            'up'    => true,
            'link'  => \Route::has('countries.index') ? route('countries.index') : '#',
        ],
        [
            'label' => 'Cities',
            'value' => $city_count ?? 0,
            'icon'  => 'fas fa-city',
            'bg'    => 'linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%)',
            'soft'  => 'rgba(14, 165, 233, 0.15)',
            'trend' => '+2.8%',
            'up'    => true,
            'link'  => \Route::has('cities.index') ? route('cities.index') : '#',
        ],
        [
            'label' => 'States',
            'value' => $state_count ?? 0,
            'icon'  => 'fas fa-map-marked-alt',
            'bg'    => 'linear-gradient(135deg, #f59e0b 0%, #b45309 100%)',
            'soft'  => 'rgba(245, 158, 11, 0.15)',
            'trend' => '-1.4%',
            'up'    => false,
            'link'  => \Route::has('states.index') ? route('states.index') : '#',
        ],
        [
            'label' => 'Departments',
            'value' => $department_count ?? 0,
            'icon'  => 'fas fa-tasks',
            'bg'    => 'linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)',
            'soft'  => 'rgba(239, 68, 68, 0.15)',
            'trend' => '+0.9%',
            'up'    => true,
            'link'  => \Route::has('departments.index') ? route('departments.index') : '#',
        ],
        [
            'label' => 'Employees',
            'value' => $employee_count ?? 0,
            'icon'  => 'fas fa-users',
            'bg'    => 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)',
            'soft'  => 'rgba(139, 92, 246, 0.15)',
            'trend' => '+6.1%',
            'up'    => true,
            'link'  => \Route::has('employees.index') ? route('employees.index') : '#',
        ],
    ];
@endphp

<div class="stat-grid-wrap">
    <div class="stat-grid">
        @foreach ($cards as $card)
            <a href="{{ $card['link'] }}"
               class="stat-tile"
               style="--grad: {{ $card['bg'] }}; --soft: {{ $card['soft'] }};">

                {{-- Decorative blurred orb --}}
                <span class="stat-orb" aria-hidden="true"></span>

                {{-- Top: icon + trend --}}
                <div class="stat-tile__top">
                    <span class="stat-tile__icon">
                        <i class="{{ $card['icon'] }}" aria-hidden="true"></i>
                    </span>
                    <span class="stat-tile__trend {{ $card['up'] ? 'is-up' : 'is-down' }}">
                        <i class="fas fa-arrow-{{ $card['up'] ? 'up' : 'down' }}"
                           aria-hidden="true"></i>
                        {{ $card['trend'] }}
                    </span>
                </div>

                {{-- Body: value + label --}}
                <div class="stat-tile__body">
                    <div class="stat-tile__value">{{ number_format($card['value']) }}</div>
                    <div class="stat-tile__label">{{ $card['label'] }}</div>
                </div>

                {{-- Footer: subtle action hint --}}
                <div class="stat-tile__footer">
                    <span>View details</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </div>
            </a>
        @endforeach
    </div>
</div>

<style>
/* ============================================================
   MODERN STAT GRID
   ============================================================ */
.stat-grid-wrap {
    --gap: 1rem;
    --radius: 1.15rem;
    margin-bottom: 1.5rem;
}

/* Responsive auto-fit grid — no Bootstrap cols needed */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: var(--gap);
    align-items: stretch;
}

/* Tile */
.stat-tile {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 0.85rem;
    padding: 1.15rem 1.15rem 1rem;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: var(--radius);
    text-decoration: none !important;
    color: inherit;
    overflow: hidden;
    isolation: isolate;
    min-height: 150px;
    transition:
        transform 0.25s cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1),
        border-color 0.25s ease;
}
.stat-tile::before {
    /* Gradient top bar */
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 4px;
    background: var(--grad);
    z-index: 2;
}
.stat-tile:hover {
    transform: translateY(-4px);
    box-shadow:
        0 20px 40px -20px rgba(15, 23, 42, 0.25),
        0 8px 16px -8px rgba(15, 23, 42, 0.08);
    border-color: rgba(15, 23, 42, 0.10);
    color: inherit;
    text-decoration: none;
}

/* Decorative blurred orb behind content */
.stat-orb {
    position: absolute;
    top: -40px;
    right: -40px;
    width: 130px;
    height: 130px;
    background: var(--grad);
    border-radius: 50%;
    opacity: 0.10;
    filter: blur(20px);
    z-index: 0;
    transition: opacity 0.25s ease, transform 0.25s ease;
}
.stat-tile:hover .stat-orb {
    opacity: 0.20;
    transform: scale(1.15);
}

/* Ensure content sits above orb */
.stat-tile > *:not(.stat-orb) {
    position: relative;
    z-index: 1;
}

/* Top row */
.stat-tile__top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.5rem;
}

/* Icon bubble */
.stat-tile__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 0.75rem;
    background: var(--grad);
    color: #ffffff;
    font-size: 1rem;
    box-shadow: 0 8px 20px -8px var(--soft);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.stat-tile:hover .stat-tile__icon {
    transform: rotate(-6deg) scale(1.08);
}

/* Trend pill */
.stat-tile__trend {
    display: inline-flex;
    align-items: center;
    gap: 0.28rem;
    padding: 0.22rem 0.55rem;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.01em;
    border-radius: 999px;
    line-height: 1;
    white-space: nowrap;
}
.stat-tile__trend i { font-size: 0.55rem; }
.stat-tile__trend.is-up {
    color: #047857;
    background: rgba(16, 185, 129, 0.12);
}
.stat-tile__trend.is-down {
    color: #b91c1c;
    background: rgba(239, 68, 68, 0.12);
}

/* Body */
.stat-tile__body {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}
.stat-tile__value {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.03em;
    color: #0f172a;
    font-variant-numeric: tabular-nums;
}
.stat-tile__label {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #64748b;
}

/* Footer */
.stat-tile__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.72rem;
    font-weight: 600;
    color: #94a3b8;
    padding-top: 0.55rem;
    border-top: 1px dashed rgba(15, 23, 42, 0.08);
    transition: color 0.2s ease;
}
.stat-tile__footer i {
    font-size: 0.62rem;
    transition: transform 0.2s ease;
}
.stat-tile:hover .stat-tile__footer {
    color: #0f172a;
}
.stat-tile:hover .stat-tile__footer i {
    transform: translateX(4px);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 767.98px) {
    .stat-grid {
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    }
    .stat-tile { min-height: 135px; padding: 1rem; }
    .stat-tile__value { font-size: 1.5rem; }
    .stat-tile__icon { width: 36px; height: 36px; font-size: 0.9rem; }
}
@media (max-width: 479.98px) {
    .stat-grid { grid-template-columns: 1fr; }
}

/* ============================================================
   DARK MODE (optional)
   ============================================================ */
.dark-mode .stat-tile {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.06);
}
.dark-mode .stat-tile__value { color: #f1f5f9; }
.dark-mode .stat-tile__label { color: #94a3b8; }
.dark-mode .stat-tile__footer {
    color: #64748b;
    border-top-color: rgba(255, 255, 255, 0.08);
}
.dark-mode .stat-tile:hover .stat-tile__footer { color: #f1f5f9; }
</style>