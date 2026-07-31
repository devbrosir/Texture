<x-filament-panels::page>
    <div x-data="dashboardCharts()" x-init="initCharts()" @date-range-updated.window="refreshCharts()">
        {{-- Date Range Filter --}}
        <div
            class="flex flex-wrap items-center justify-between gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">بازه زمانی:</span>
                <div class="flex gap-2">
                    @php
                        $ranges = [
                            'today' => 'امروز',
                            'week' => 'هفته اخیر',
                            'month' => 'ماه اخیر',
                        ];
                    @endphp
                    @foreach($ranges as $key => $label)
                        <button
                            wire:click="updateDateRange('{{ $key }}')"
                            class="px-3 py-1 text-sm rounded-lg transition-colors {{ $this->dateRange === $key ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                نمایش: {{ $dateRangeLabel }}
            </span>
        </div>

        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">انتخاب فضا</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mostSelectedSceneCategories ? array_sum(array_column($mostSelectedSceneCategories, 'count')) : 0 }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">انتخاب محیط</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mostSelectedScenes ? array_sum(array_column($mostSelectedScenes, 'count')) : 0 }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">انتخاب تکسچر</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $mostSelectedTextures ? array_sum(array_column($mostSelectedTextures, 'count')) : 0 }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">کل فعالیت‌ها</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ array_sum($dailyActivities) }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            {{-- Daily Activities Chart --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">فعالیت‌های روزانه</h3>
                <div class="h-64 relative">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            {{-- Activities Distribution --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">توزیع فعالیت‌ها</h3>
                <div class="h-64 relative">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Lists --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

            {{-- Most Selected Scene Categories --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">فضاهای پرانتخاب</h3>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse($mostSelectedSceneCategories as $category)
                        <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300 truncate flex-1">{{ $category['title'] }}</span>
                            <span
                                class="text-sm font-medium text-primary-600 dark:text-primary-400">{{ $category['count'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">داده‌ای برای نمایش وجود
                            ندارد</p>
                    @endforelse
                </div>
            </div>

            {{-- Most Selected Scenes --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">محیط‌های پرانتخاب</h3>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse($mostSelectedScenes as $scene)
                        <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300 truncate flex-1">{{ $scene['title'] }}</span>
                            <span
                                class="text-sm font-medium text-primary-600 dark:text-primary-400">{{ $scene['count'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">داده‌ای برای نمایش وجود
                            ندارد</p>
                    @endforelse
                </div>
            </div>

            {{-- Most Selected Textures --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">تکسچرهای پرانتخاب</h3>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse($mostSelectedTextures as $texture)
                        <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300 truncate flex-1">{{ $texture['title'] }}</span>
                            <span
                                class="text-sm font-medium text-primary-600 dark:text-primary-400">{{ $texture['count'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">داده‌ای برای نمایش وجود
                            ندارد</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{asset('js/chart-v4.5.1.js')}}"></script>
        <script>
            function dashboardCharts() {
                return {
                    dailyChartInstance: null,
                    distributionChartInstance: null,
                    chartData: null,

                    initCharts() {
                        // Get initial data from Livewire
                        this.chartData = {
                            daily: @json($dailyActivities),
                            dailyLabels: @json($dailyActivitiesLabels),
                            overview: @json($activitiesOverview)
                        };

                        // Initialize charts after a small delay to ensure DOM is ready
                        setTimeout(() => {
                            this.renderCharts();
                        }, 100);
                    },

                    refreshCharts() {
                        // Get fresh data from Livewire
                        this.chartData = {
                            daily: @json($dailyActivities),
                            dailyLabels: @json($dailyActivitiesLabels),
                            overview: @json($activitiesOverview)
                        };

                        // Destroy existing charts
                        this.destroyCharts();

                        // Re-render charts
                        setTimeout(() => {
                            this.renderCharts();
                        }, 100);
                    },

                    renderCharts() {
                        this.renderDailyChart();
                        this.renderDistributionChart();
                    },

                    destroyCharts() {
                        if (this.dailyChartInstance) {
                            this.dailyChartInstance.destroy();
                            this.dailyChartInstance = null;
                        }
                        if (this.distributionChartInstance) {
                            this.distributionChartInstance.destroy();
                            this.distributionChartInstance = null;
                        }
                    },

                    renderDailyChart() {
                        const canvas = document.getElementById('dailyChart');
                        if (!canvas) return;

                        const ctx = canvas.getContext('2d');
                        const dailyData = this.chartData.daily || {};
                        const labels = this.chartData.dailyLabels || Object.keys(dailyData);
                        const values = Object.values(dailyData);

                        this.dailyChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'تعداد فعالیت‌ها',
                                    data: values,
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#3b82f6',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                return context.parsed.y + ' فعالیت';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1,
                                            font: {
                                                size: 11
                                            }
                                        },
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.05)'
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            font: {
                                                size: 10
                                            },
                                            maxRotation: 45,
                                            minRotation: 0
                                        }
                                    }
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                }
                            }
                        });
                    },

                    renderDistributionChart() {
                        const canvas = document.getElementById('distributionChart');
                        if (!canvas) return;

                        const ctx = canvas.getContext('2d');
                        const overviewData = this.chartData.overview || {};
                        const labels = Object.keys(overviewData);
                        const values = Object.values(overviewData);

                        const colors = [
                            '#3b82f6', '#10b981', '#8b5cf6', '#f59e0b',
                            '#ef4444', '#06b6d4', '#f472b6', '#6366f1',
                            '#14b8a6', '#f97316', '#8b5cf6', '#ec4899'
                        ];

                        this.distributionChartInstance = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: values,
                                    backgroundColor: colors.slice(0, labels.length),
                                    borderWidth: 2,
                                    borderColor: '#ffffff',
                                    hoverOffset: 8,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '55%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 15,
                                            font: {
                                                size: 11,
                                                weight: '500'
                                            },
                                            usePointStyle: true,
                                            pointStyle: 'circle'
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                };
            }

            // Also re-render on window resize
            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    const charts = document.querySelector('[x-data="dashboardCharts()"]')?.__x;
                    if (charts && charts.renderCharts) {
                        charts.renderCharts();
                    }
                }, 250);
            });
        </script>
    @endpush
</x-filament-panels::page>
