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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class SeoSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?array $data = [];

    public ?array $oldSettings = null;

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'تنظیمات SEO';

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->oldSettings = Setting::get('seo');
        $this->form->fill($this->oldSettings);
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

        $oldImage = Arr::get($this->oldSettings, 'og.image');
        $newImage = Arr::get($data, 'og.image');
        if ($oldImage && $oldImage !== $newImage) {
            Storage::disk('public')->delete($oldImage);
        }

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
