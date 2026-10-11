{{-- ============================================================
     CHART.JS SETUP
============================================================ --}}
@push('dashboard_style')
    @once
        <style>
            .chart-card {
                background: #ffffff;
                border-radius: 1rem;
                border: 1px solid rgba(15, 23, 42, 0.06);
                box-shadow: 0 4px 24px -8px rgba(15, 23, 42, 0.08);
                overflow: hidden;
                transition: box-shadow 0.25s ease;
            }
            .chart-card:hover {
                box-shadow: 0 12px 32px -8px rgba(15, 23, 42, 0.12);
            }

            .chart-card__header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: 1.15rem 1.35rem;
                border-bottom: 1px solid rgba(15, 23, 42, 0.06);
                flex-wrap: wrap;
            }
            .chart-card__title-group {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 0;
            }
            .chart-card__icon {
                width: 38px; height: 38px;
                border-radius: 0.65rem;
                background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
                color: #4f46e5;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.95rem;
                flex-shrink: 0;
            }
            .chart-card__title {
                font-size: 1rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
                line-height: 1.3;
            }
            .chart-card__subtitle {
                font-size: 0.75rem;
                color: #64748b;
                margin: 0.1rem 0 0;
            }

            /* Range selector */
            .chart-range {
                display: inline-flex;
                background: #f1f5f9;
                border-radius: 0.55rem;
                padding: 0.2rem;
                gap: 0.15rem;
            }
            .chart-range__btn {
                appearance: none;
                border: 0;
                background: transparent;
                font-size: 0.72rem;
                font-weight: 600;
                color: #64748b;
                padding: 0.35rem 0.75rem;
                border-radius: 0.4rem;
                cursor: pointer;
                transition: all 0.2s ease;
                white-space: nowrap;
            }
            .chart-range__btn:hover { color: #334155; }
            .chart-range__btn.is-active {
                background: #ffffff;
                color: #4f46e5;
                box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
            }

            .chart-card__body {
                padding: 1.25rem 1.35rem 1.5rem;
                position: relative;
                height: 340px;
            }
            .chart-card__canvas { width: 100% !important; height: 100% !important; }

            /* Legend (custom) */
            .chart-legend {
                display: flex;
                align-items: center;
                gap: 1.25rem;
                flex-wrap: wrap;
                padding: 0 1.35rem 1.25rem;
            }
            .chart-legend__item {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                font-size: 0.78rem;
                color: #475569;
                font-weight: 500;
            }
            .chart-legend__dot {
                width: 10px; height: 10px;
                border-radius: 3px;
                flex-shrink: 0;
            }

            /* Dark mode */
            .dark-mode .chart-card,
            .dark .chart-card {
                background: #111827;
                border-color: rgba(255, 255, 255, 0.06);
            }
            .dark-mode .chart-card__header,
            .dark .chart-card__header {
                border-bottom-color: rgba(255, 255, 255, 0.06);
            }
            .dark-mode .chart-card__title,
            .dark .chart-card__title { color: #f1f5f9; }
            .dark-mode .chart-card__subtitle,
            .dark .chart-card__subtitle { color: #94a3b8; }
            .dark-mode .chart-card__icon,
            .dark .chart-card__icon {
                background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
                color: #a5b4fc;
            }
            .dark-mode .chart-range,
            .dark .chart-range { background: #1e293b; }
            .dark-mode .chart-range__btn,
            .dark .chart-range__btn { color: #94a3b8; }
            .dark-mode .chart-range__btn.is-active,
            .dark .chart-range__btn.is-active {
                background: #334155;
                color: #c7d2fe;
            }
            .dark-mode .chart-legend__item,
            .dark .chart-legend__item { color: #94a3b8; }

            @media (max-width: 575.98px) {
                .chart-card__body { height: 280px; padding: 1rem; }
                .chart-card__header { padding: 1rem; }
            }
        </style>
    @endonce
@endpush


{{-- ============================================================
     MARKUP
============================================================ --}}
<div class="chart-card" id="revenue-chart-card">
    <div class="chart-card__header">
        <div class="chart-card__title-group">
            <span class="chart-card__icon">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
            </span>
            <div>
                <h3 class="chart-card__title">Revenue &amp; Expenses Overview</h3>
                <p class="chart-card__subtitle">Track your financial performance over time</p>
            </div>
        </div>

        <div class="chart-range" role="tablist" aria-label="Chart range selector">
            <button type="button" class="chart-range__btn" data-range="7d">7D</button>
            <button type="button" class="chart-range__btn is-active" data-range="30d">30D</button>
            <button type="button" class="chart-range__btn" data-range="6m">6M</button>
            <button type="button" class="chart-range__btn" data-range="1y">1Y</button>
        </div>
    </div>

    <div class="chart-card__body">
        <canvas id="revenueChart" class="chart-card__canvas" aria-label="Revenue chart" role="img"></canvas>
    </div>

    <div class="chart-legend">
        <span class="chart-legend__item">
            <span class="chart-legend__dot" style="background:#6366f1"></span> Revenue
        </span>
        <span class="chart-legend__item">
            <span class="chart-legend__dot" style="background:#f43f5e"></span> Expenses
        </span>
        <span class="chart-legend__item">
            <span class="chart-legend__dot" style="background:#10b981"></span> Profit
        </span>
    </div>
</div>


{{-- ============================================================
     SCRIPTS — Chart.js
============================================================ --}}
@push('dashboard_script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            'use strict';

            // ---- Data injected from controller ----
            const datasets = @json($chartData ?? []);

            // Fallback if controller didn't provide data
            if (!datasets || Object.keys(datasets).length === 0) {
                console.warn('No chartData provided — using fallback sample.');
                window.location.reload(); // or set a default inline
                return;
            }

            Chart.defaults.font.family = "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#64748b';

            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;

            const canvasGradientRevenue = ctx.getContext('2d').createLinearGradient(0, 0, 0, 340);
            canvasGradientRevenue.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
            canvasGradientRevenue.addColorStop(1, 'rgba(99, 102, 241, 0.00)');

            const canvasGradientExpenses = ctx.getContext('2d').createLinearGradient(0, 0, 0, 340);
            canvasGradientExpenses.addColorStop(0, 'rgba(244, 63, 94, 0.30)');
            canvasGradientExpenses.addColorStop(1, 'rgba(244, 63, 94, 0.00)');

            const canvasGradientProfit = ctx.getContext('2d').createLinearGradient(0, 0, 0, 340);
            canvasGradientProfit.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            canvasGradientProfit.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

            function buildConfig(range) {
                const data = datasets[range] || datasets['30d'];
                const profit = data.revenue.map((v, i) => v - data.expenses[i]);

                return {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Revenue',
                                data: data.revenue,
                                borderColor: '#6366f1',
                                backgroundColor: canvasGradientRevenue,
                                borderWidth: 2.5,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                pointHoverBackgroundColor: '#6366f1',
                                pointHoverBorderColor: '#ffffff',
                                pointHoverBorderWidth: 3,
                            },
                            {
                                label: 'Expenses',
                                data: data.expenses,
                                borderColor: '#f43f5e',
                                backgroundColor: canvasGradientExpenses,
                                borderWidth: 2.5,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                pointHoverBackgroundColor: '#f43f5e',
                                pointHoverBorderColor: '#ffffff',
                                pointHoverBorderWidth: 3,
                            },
                            {
                                label: 'Profit',
                                data: profit,
                                borderColor: '#10b981',
                                backgroundColor: canvasGradientProfit,
                                borderWidth: 2,
                                borderDash: [5, 4],
                                tension: 0.4,
                                fill: false,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                pointHoverBackgroundColor: '#10b981',
                                pointHoverBorderColor: '#ffffff',
                                pointHoverBorderWidth: 3,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                titleColor: '#f1f5f9',
                                bodyColor: '#e2e8f0',
                                borderColor: 'rgba(255,255,255,0.08)',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 10,
                                titleFont: { weight: '700', size: 12 },
                                bodyFont: { size: 12 },
                                displayColors: true,
                                boxPadding: 6,
                                callbacks: {
                                    label: function (context) {
                                        return ' ' + context.dataset.label + ': $' +
                                            context.parsed.y.toLocaleString();
                                    },
                                },
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false, drawBorder: false },
                                ticks: { color: '#94a3b8', font: { weight: '600', size: 11 }, padding: 8 },
                                border: { display: false },
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.15)',
                                    drawBorder: false,
                                    borderDash: [4, 4],
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { weight: '600', size: 11 },
                                    padding: 10,
                                    callback: function (value) {
                                        if (value >= 1000) return '$' + (value / 1000) + 'k';
                                        return '$' + value;
                                    },
                                },
                                border: { display: false },
                            },
                        },
                        animation: { duration: 800, easing: 'easeOutQuart' },
                    },
                };
            }

            const chart = new Chart(ctx, buildConfig('30d'));

            document.querySelectorAll('.chart-range__btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.chart-range__btn')
                        .forEach(b => b.classList.remove('is-active'));
                    this.classList.add('is-active');

                    const range = this.dataset.range;
                    chart.data = buildConfig(range).data;
                    chart.update();
                });
            });

            function applyDarkChartTheme() {
                const isDark = document.documentElement.classList.contains('dark')
                    || document.body.classList.contains('dark-mode');

                Chart.defaults.color = isDark ? '#94a3b8' : '#64748b';
                chart.options.scales.x.ticks.color = isDark ? '#64748b' : '#94a3b8';
                chart.options.scales.y.ticks.color = isDark ? '#64748b' : '#94a3b8';
                chart.options.scales.y.grid.color = isDark
                    ? 'rgba(255,255,255,0.06)'
                    : 'rgba(148,163,184,0.15)';
                chart.update('none');
            }

            const themeObserver = new MutationObserver(applyDarkChartTheme);
            themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            themeObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
            applyDarkChartTheme();

            window.revenueChart = chart;
        })();
    </script>
@endpush