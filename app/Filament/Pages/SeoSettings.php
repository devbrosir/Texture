<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class SeoSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?array $data = [];

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'تنظیمات SEO';

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $data = Setting::get('seo');
        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema(SeoSettingsForm::scheme());
    }

    public function save(): void
    {
        $data = $this->form->getState();
        Setting::updateValue($data, 'seo');
        Notification::make()
            ->success()
            ->body('تنظیمات ذخیره شد')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('ذخیره')
                ->action('save'),
        ];
    }
}
