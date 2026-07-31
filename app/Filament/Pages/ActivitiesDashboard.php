<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Scene;
use App\Models\SceneCategory;
use App\Models\Texture;
use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class ActivitiesDashboard extends Page
{
    public ?string $dateRange = 'week';

    public ?string $startDate = null;

    public ?string $endDate = null;

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected string $view = 'filament.pages.activities-dashboard';

    protected static string|null|UnitEnum $navigationGroup = 'آمار و گزارش‌ها';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'داشبورد فعالیت‌ها';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getViewData(): array
    {
        $dates = $this->getDateRange();

        return [
            'mostSelectedScenes' => $this->getMostSelectedScenes($dates),
            'mostSelectedTextures' => $this->getMostSelectedTextures($dates),
            'mostSelectedSceneCategories' => $this->getMostSelectedSceneCategories($dates),
            'activitiesOverview' => $this->getActivitiesOverview($dates),
            'dailyActivities' => $this->getDailyActivities($dates),
            'dailyActivitiesLabels' => $this->getDailyActivitiesLabels($dates),
            'dateRangeLabel' => $this->getDateRangeLabel(),
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('today')
                    ->label('امروز')
                    ->action(fn () => $this->updateDateRange('today')),
                Action::make('week')
                    ->label('هفته اخیر')
                    ->action(fn () => $this->updateDateRange('week')),
                Action::make('month')
                    ->label('ماه اخیر')
                    ->action(fn () => $this->updateDateRange('month')),
            ])
                ->label('بازه زمانی')
                ->icon('heroicon-o-calendar')
                ->color(Color::Blue),
        ];
    }

    public function updateDateRange(string $range): void
    {
        $this->dateRange = $range;
        $this->startDate = null;
        $this->endDate = null;
        $this->dispatch('date-range-updated');
    }

    protected function getDateRangeLabel(): string
    {
        return match ($this->dateRange) {
            'today' => 'امروز',
            'month' => 'ماه اخیر',
            default => 'هفته اخیر',
        };
    }

    protected function getDateRange(): array
    {
        $now = Date::now();

        return match ($this->dateRange) {
            'today' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'month' => [
                'start' => $now->copy()->subDays(30)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            default => [
                'start' => $now->copy()->subDays(7)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
        };
    }

    protected function getMostSelectedScenes(array $dates): array
    {
        $results = Activity::query()
            ->where('type', ActivityType::SelectScene->value)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->whereNotNull('related_id')
            ->select('related_id', DB::raw('count(*) as total'))
            ->groupBy('related_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        if ($results->isEmpty()) {
            return [];
        }

        $sceneIds = $results->pluck('related_id')->toArray();
        $scenes = Scene::query()->whereIn('id', $sceneIds)->get()->keyBy('id');

        return $results->map(function ($item) use ($scenes): array {
            $scene = $scenes->get($item->related_id);

            return [
                'id' => $item->related_id,
                'title' => $scene?->title ?? 'نامشخص',
                'count' => $item->total,
            ];
        })->all();
    }

    protected function getMostSelectedTextures(array $dates): array
    {
        $results = Activity::query()
            ->where('type', ActivityType::SelectTexture->value)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->whereNotNull('related_id')
            ->select('related_id', DB::raw('count(*) as total'))
            ->groupBy('related_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        if ($results->isEmpty()) {
            return [];
        }

        $textureIds = $results->pluck('related_id')->toArray();
        $textures = Texture::query()->whereIn('id', $textureIds)->get()->keyBy('id');

        return $results->map(function ($item) use ($textures): array {
            $texture = $textures->get($item->related_id);

            return [
                'id' => $item->related_id,
                'title' => $texture?->title ?? 'نامشخص',
                'count' => $item->total,
            ];
        })->all();
    }

    protected function getMostSelectedSceneCategories(array $dates): array
    {
        $results = Activity::query()
            ->where('type', ActivityType::SelectSceneCategory->value)
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->whereNotNull('related_id')
            ->select('related_id', DB::raw('count(*) as total'))
            ->groupBy('related_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        if ($results->isEmpty()) {
            return [];
        }

        $categoryIds = $results->pluck('related_id')->toArray();
        $categories = SceneCategory::query()->whereIn('id', $categoryIds)->get()->keyBy('id');

        return $results->map(function ($item) use ($categories): array {
            $category = $categories->get($item->related_id);

            return [
                'id' => $item->related_id,
                'title' => $category?->title ?? 'نامشخص',
                'count' => $item->total,
            ];
        })->all();
    }

    protected function getActivitiesOverview(array $dates): array
    {
        $types = [
            'select_scene' => 'انتخاب فضا',
            'select_texture' => 'انتخاب تکسچر',
            'select_scene_category' => 'انتخاب کاربری',
            'view_page' => 'مشاهده صفحه',
            'click_detail' => 'کلیک روی جزئیات',
            'download' => 'دانلود',
            'login_success' => 'ورود موفق',
        ];

        $data = Activity::query()
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->whereIn('type', array_keys($types))
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) use ($types): array {
                // $item->type is a string, not an enum because we're selecting from database
                $typeKey = $item->type;

                return [$types[$typeKey->value] ?? $typeKey->value => $item->total];
            })
            ->toArray();

        // Ensure all types are present with 0 count
        foreach ($types as $label) {
            if (! isset($data[$label])) {
                $data[$label] = 0;
            }
        }

        return $data;
    }

    protected function getDailyActivities(array $dates): array
    {
        // محاسبه تعداد روزها
        $daysCount = $dates['start']->diffInDays($dates['end']) + 1;

        // اگر تعداد روزها بیشتر از 90 بود، محدود کن
        if ($daysCount > 90) {
            $dates['start'] = $dates['end']->copy()->subDays(90);
            $daysCount = 91;
        }

        $days = [];
        $current = $dates['start']->copy();

        // استفاده از array با کلیدهای عددی برای سرعت بیشتر
        $dayKeys = [];
        for ($i = 0; $i < $daysCount; $i++) {
            $key = $current->format('Y-m-d');
            $dayKeys[$i] = $key;
            $days[$key] = 0;
            $current = $current->addDay();
        }

        // کوئری دیتابیس
        $activities = Activity::query()
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // مقداردهی
        foreach ($dayKeys as $key) {
            $days[$key] = $activities[$key] ?? 0;
        }

        return $days;
    }

    /**
     * Get Persian (Jalali) labels for daily activities chart
     */
    protected function getDailyActivitiesLabels(array $dates): array
    {
        $labels = [];
        $current = $dates['start']->copy();

        // محاسبه تعداد روزها
        $daysCount = $dates['start']->diffInDays($dates['end']) + 1;

        // اگر تعداد روزها بیشتر از 90 بود، محدود کن
        if ($daysCount > 90) {
            $current = $dates['end']->copy()->subDays(90);
            $daysCount = 91;
        }

        // استفاده از حلقه for به جای while برای سرعت بیشتر
        for ($i = 0; $i < $daysCount; $i++) {
            try {
                $jalali = $current->toJalali();
                $labels[] = $jalali->format('d').' '.$jalali->format('F');
            } catch (Exception) {
                // در صورت خطا، از تاریخ میلادی استفاده کن
                $labels[] = $current->format('d M');
            }
            $current = $current->addDay();
        }

        return $labels;
    }
}
