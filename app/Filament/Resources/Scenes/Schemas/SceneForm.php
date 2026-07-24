<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes\Schemas;

use App\Models\Scene;
use App\Models\SceneCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

final class SceneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->label('نام محیط')->required(),
                TagsInput::make('tags')->label('تگ ها'),
                SpatieMediaLibraryFileUpload::make('image')->label('تصویر (فرمت jpg/jpeg)')
                    ->collection(Scene::IMAGE)
                    ->disk('public')
                    ->maxSize(4096)
                    ->acceptedFileTypes(['image/jpeg'])
                    ->columnSpanFull(),
                Select::make('category_id')->label('دسته')
                    ->required()
                    ->options(SceneCategory::query()->pluck('title', 'id'))
                    ->createOptionForm([
                        TextInput::make('title')->required(),
                        FileUpload::make('category_image')->label('تصویر')
                            ->disk('public')
                            ->directory('temp-category-images')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/*'])
                            ->columnSpanFull(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $category = SceneCategory::query()->create(['title' => $data['title']]);
                        if (! empty($data['category_image'])) {
                            $category
                                ->addMediaFromDisk($data['category_image'], 'public')
                                ->toMediaCollection(SceneCategory::IMAGE);
                            Storage::disk('public')->delete($data['category_image']);
                        }

                        return $category->id;
                    }),
                TextEntry::make('description')->hiddenLabel()
                    ->hiddenOn('edit')
                    ->color('warning')
                    ->extraAttributes(['style' => 'font-weight: bold;'])
                    ->default('برای ایجاد بخش ها، ابتدا محیط را ذخیره کنید.'),
            ]);
    }
}
