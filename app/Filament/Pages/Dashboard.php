<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\RequestStatus;
use App\Models\Banner;
use App\Models\ProcessRequest;
use App\Models\Scene;
use App\Models\Texture;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Modules\User\Models\User;

class Dashboard extends Page
{
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'داشبورد';

    protected static ?int $navigationSort = -1;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    /**
     * Get the quick access cards data
     */
    public function getQuickAccessCards(): array
    {
        return [
            [
                'title' => 'محیط‌ها',
                'description' => 'مدیریت محیط‌ها و بخش‌ها',
                'icon' => Heroicon::OutlinedRectangleStack,
                'color' => 'blue',
                'route' => 'filament.admin.resources.scenes.index',
                'badge' => Scene::query()->count(),
            ],
            [
                'title' => 'تکسچرها',
                'description' => 'مدیریت تکسچرها و متریال‌ها',
                'icon' => Heroicon::OutlinedPhoto,
                'color' => 'green',
                'route' => 'filament.admin.resources.textures.index',
                'badge' => Texture::query()->count(),
            ],
            [
                'title' => 'دسته‌بندی تکسچر',
                'description' => 'مدیریت دسته‌بندی‌ها و انواع تکسچر',
                'icon' => Heroicon::OutlinedTag,
                'color' => 'purple',
                'route' => 'filament.admin.resources.texture-types.index',
            ],
            [
                'title' => 'فضاها',
                'description' => 'مدیریت دسته‌بندی محیط‌ها',
                'icon' => Heroicon::OutlinedFolder,
                'color' => 'indigo',
                'route' => 'filament.admin.resources.scene-categories.index',
            ],
            [
                'title' => 'بنرها',
                'description' => 'مدیریت بنرهای تبلیغاتی',
                'icon' => Heroicon::OutlinedRectangleGroup,
                'color' => 'orange',
                'route' => 'filament.admin.resources.banners.index',
                'badge' => Banner::query()->where('active', true)->count(),
            ],
            [
                'title' => 'کاربران',
                'description' => 'مدیریت کاربران سیستم',
                'icon' => Heroicon::OutlinedUsers,
                'color' => 'pink',
                'route' => 'filament.admin.resources.users.index',
                'badge' => User::query()->count(),
            ],
            [
                'title' => 'درخواست‌ها',
                'description' => 'مدیریت درخواست‌های کاربران',
                'icon' => Heroicon::OutlinedClipboardDocumentList,
                'color' => 'red',
                'route' => 'filament.admin.resources.process-requests.index',
                'badge' => ProcessRequest::query()->where('status', RequestStatus::PENDING)->count(),
            ],
            [
                'title' => 'تنظیمات',
                'description' => 'تنظیمات عمومی سیستم',
                'icon' => Heroicon::OutlinedCog6Tooth,
                'color' => 'gray',
                'route' => 'filament.admin.resources.settings.index',
            ],
            [
                'title' => 'گزارش فعالیت‌ها',
                'description' => 'مشاهده آمار و نمودارهای فعالیت',
                'icon' => Heroicon::OutlinedChartBar,
                'color' => 'cyan',
                'route' => 'filament.admin.pages.activities-dashboard',
            ],
        ];
    }

    /**
     * Get quick stats for dashboard
     */
    public function getQuickStats(): array
    {
        return [
            [
                'label' => 'محیط‌ها',
                'value' => Scene::query()->count(),
                'icon' => Heroicon::OutlinedRectangleStack,
                'color' => 'blue',
            ],
            [
                'label' => 'تکسچرها',
                'value' => Texture::query()->count(),
                'icon' => Heroicon::OutlinedPhoto,
                'color' => 'green',
            ],
            [
                'label' => 'کاربران',
                'value' => User::query()->count(),
                'icon' => Heroicon::OutlinedUsers,
                'color' => 'pink',
            ],
            [
                'label' => 'درخواست‌ها',
                'value' => ProcessRequest::query()->where('status', RequestStatus::PENDING)->count(),
                'icon' => Heroicon::OutlinedClipboardDocumentList,
                'color' => 'red',
            ],
        ];
    }

    public function getViewData(): array
    {
        return [
            'quickStats' => $this->getQuickStats(),
            'quickAccessCards' => $this->getQuickAccessCards(),
        ];
    }
}
