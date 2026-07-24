<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes\RelationManagers;

use App\Enums\TextureType;
use App\Filament\Forms\Components\TreeCheckboxField;
use App\Filament\Pages\Editor;
use App\Models\Part;
use App\Models\Texture;
use App\Models\TextureCategory;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PartsRelationManager extends RelationManager
{
    protected static string $relationship = 'parts';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $relationshipTitle = 'بخش‌ها';

    public static function getModelLabel(): string
    {
        return 'بخش';
    }

    public static function getPluralLabel(): string
    {
        return 'بخش‌ها';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')->label('نام بخش')
                    ->required()
                    ->maxLength(255),
                Select::make('type')->label('نوع بخش')
                    ->options(TextureType::toOptions())
                    ->required(),
                TreeCheckboxField::make('selected_types')
                    ->label('دسته‌بندی‌ها')
                    ->default([1, 4, 5])
                    ->options(TextureCategory::tree()),
                SpatieMediaLibraryFileUpload::make('mask')
                    ->label('ماسک')
                    ->collection(Part::MASK)
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right bottom')
                    ->uploadButtonPosition('left bottom')
                    ->uploadProgressIndicatorPosition('left bottom')
                    ->acceptedFileTypes(['image/*'])
                    ->disk('public')
                    ->maxSize(4096),
                Select::make('default_texture_id')->label('تکسچر پیش فرض')
                    ->options(Texture::all()->pluck('title', 'id'))
                    ->relationship('defaultTexture', 'title')
                    ->native(false)
                    ->allowHtml()
                    ->optionsLimit(10)
                    ->getOptionLabelFromRecordUsing(fn (Texture $record): HtmlString => new HtmlString('
        <div style="display: flex; align-items: center; gap: 12px;">
            <img src="'.asset($record->image).'"
                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
            <div style="display: flex; flex-direction: column;">
                <span style="font-weight: 500;">'.e($record->title).'</span>
            </div>
        </div>
    ')),
                TextInput::make('mask_config')
                    ->label('تنظیمات ماسک')
                    ->readonly()
                    ->disabled()
                    ->dehydrated(false) // prevent from store in database
                    ->formatStateUsing(fn (?Part $record): string => $record && $record->mask_config ? 'تنظیم شده' : 'تنظیم نشده'),

                Toggle::make('active')->label('فعال')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('نام بخش')
                    ->icon('heroicon-m-cube'),

                SpatieMediaLibraryImageColumn::make('image')->label('تصویر')
                    ->collection(Part::MASK)
                    ->imageSize(60)
                    ->disk('public')
                    ->square(),

                IconColumn::make('active')
                    ->label('وضعیت')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('ایجاد بخش جدید')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('ایجاد بخش جدید')
                    ->mutateDataUsing($this->mutateData(...)),
            ])
            ->recordActions([
                Action::make('goto_editor')
                    ->label('ویرایشگر')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn (Part $record): string => Editor::getUrl(['part_id' => $record->id]))
                    ->openUrlInNewTab(false),

                EditAction::make()
                    ->label('ویرایش')
                    ->color('warning')
                    ->icon('heroicon-o-pencil')
                    ->mutateDataUsing($this->mutateData(...)),

                DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash'),
            ])
            ->emptyStateHeading('هیچ بخشی برای این محیط وجود ندارد')
            ->emptyStateDescription('برای شروع، اولین بخش را ایجاد کنید.')
            ->emptyStateIcon('heroicon-o-cube');
    }

    private function mutateData(array $data): array
    {
        $categories = $data['selected_types'];
        $data['selected_types'] = collect($categories)->reject(fn ($c): bool => str_starts_with((string) $c, 't-'))->values()->toArray();

        return $data;
    }
}
