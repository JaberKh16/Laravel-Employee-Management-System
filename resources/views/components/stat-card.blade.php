@props([
    'label',
    'value',
    'icon'    => 'fas fa-chart-bar',
    'color'   => 'primary',   // primary|success|info|warning|danger|secondary
])

<div class="col-xl-2 col-md-4 col-sm-6 mb-4">
    <div class="card stat-card border-left-{{ $color }} shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-label font-weight-bold text-{{ $color }} text-uppercase mb-1">
                        {{ $label }}
                    </div>
                    <div class="stat-value mb-0 font-weight-bold text-gray-800">
                        {{ $value }}
                    </div>
                </div>
                <div class="col-auto">
                    <i class="{{ $icon }} fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>