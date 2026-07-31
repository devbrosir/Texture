<x-filament-panels::page>
    <div class="space-y-6 font-fd">
        {{-- Welcome Section --}}
        <div
            class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 rounded-2xl p-8 text-white shadow-lg">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        {{ __('سلام') }} {{ auth()->user()?->name ?? 'کاربر عزیز' }}
                    </h1>
                    <p class="mt-2 text-primary-100 dark:text-primary-200 text-sm">
                        {{ __('به پنل مدیریت خوش آمدید. از طریق کاردهای زیر به بخش‌های مختلف دسترسی داشته باشید.') }}
                    </p>
                </div>
                <div class="hidden sm:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <span class="text-primary-100">{{ __('امروز') }}</span>
                        <span class="font-bold mr-2">{{ verta()->format('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($quickStats as $stat)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stat['value']) }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30">
                            <x-filament::icon
                                :icon="$stat['icon']"
                                class="w-5 h-5 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Quick Access Cards --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('دسترسی سریع') }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($quickAccessCards as $card)
                    <a
                        href="{{ route($card['route']) }}"
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div
                                    class="p-2.5 rounded-lg bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-900/20 w-fit">
                                    <x-filament::icon
                                        :icon="$card['icon']"
                                        class="w-5 h-5 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400"
                                    />
                                </div>
                                <h3 class="mt-3 text-base font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                    {{ $card['title'] }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                    {{ $card['description'] }}
                                </p>
                            </div>
                            @if(isset($card['badge']) && $card['badge'] > 0)
                                <span
                                    class="inline-flex items-center justify-center min-w-6 h-6 px-2 text-xs font-medium rounded-full bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/30 text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-300">
                                    {{ number_format($card['badge']) }}
                                </span>
                            @endif
                        </div>
                        <div
                            class="mt-4 flex items-center text-sm text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400 group-hover:translate-x-1 transition-transform">
                            {{ __('ورود به بخش') }}
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Recent Activities (Optional - Lightweight) --}}
        @php
            $recentActivities = \App\Models\Activity::query()
                ->with('user')
                ->latest()
                ->limit(5)
                ->get();
        @endphp

        @if($recentActivities->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __('آخرین فعالیت‌ها') }}
                    </h2>
                    <a
                        href="{{ route('filament.admin.resources.activities.index') }}"
                        class="text-sm text-primary-600 dark:text-primary-400 hover:underline"
                    >
                        {{ __('مشاهده همه') }}
                    </a>
                </div>
                <div class="space-y-3">
                    @php
                        /** @var \Illuminate\Support\Collection<\App\Models\Activity> $recentActivities */
                    @endphp
                    @foreach($recentActivities as $activity)
                        <div
                            class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-300">
                                    {{ substr($activity->user?->name ?? 'م', 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <span class="font-medium">{{ $activity->user?->name ?? 'مهمان' }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 mx-1">•</span>
                                        <span>{{ $activity->typeTitle }}</span>
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $activity->created_at?->toJalali()->format('Y/m/d - H:i') }}
                                    </p>
                                </div>
                            </div>
                            @if($activity->related)
                                <span
                                    class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full truncate max-w-[120px]">
                                    {{ $activity->related?->title ?? '' }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
