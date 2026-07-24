<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Infolists\Components\Entry;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(config('app.name'))
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-dark.png'))
            ->renderHook(PanelsRenderHook::FOOTER, fn (): View => view('filament.footer'))
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }

    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('tree-checkbox', asset('js/tree-checkbox.js')),
        ]);

        DatePicker::configureUsing(function (DateTimePicker $datePicker): void {
            $datePicker->displayFormat('d F Y');
        });

        DateTimePicker::configureUsing(function (DateTimePicker $datePicker): void {
            $datePicker->displayFormat('d F Y - H:i:s');
        });

        Field::configureUsing(function (Field $field): void {
            $this->commonConfig($field);
        });
        Column::configureUsing(function (Column $column): void {
            $this->commonConfig($column);
        });
        Filter::configureUsing(function (Filter $filter): void {
            $this->commonConfig($filter);
        });
        Entry::configureUsing(function (Entry $entry): void {
            $this->commonConfig($entry);
        });

        Action::configureUsing(function (Action $action): void {
            $this->commonConfig($action);
        });
    }

    private function commonConfig(Field|Column|Filter|Entry|Action $component): void
    {
        $component->translateLabel();
    }
}
