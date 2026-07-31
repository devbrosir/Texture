<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;

final class SeoSettingsForm
{
    public static function scheme(): array
    {
        return [
            Group::make()
                ->schema([
                    TextInput::make('title')
                        ->label('Title Tag')
                        ->maxLength(60),

                    Textarea::make('description')
                        ->label('Meta Description')
                        ->rows(3)
                        ->maxLength(160),

                    Select::make('robots')
                        ->label('Robots Meta')
                        ->options([
                            'index,follow' => 'index, follow',
                            'noindex,follow' => 'noindex, follow',
                            'noindex,nofollow' => 'noindex, nofollow',
                        ])
                        ->default('index,follow'),
                ]),

            Group::make()
                ->schema([
                    TextInput::make('og.title')->label('OG Title'),
                    Textarea::make('og.description')->label('OG Description'),
                    TextInput::make('og.image')->label('OG Image URL'),
                ]),

            Group::make()
                ->schema([
                    Select::make('twitter.card')
                        ->options([
                            'summary' => 'Summary',
                            'summary_large_image' => 'Summary Large Image',
                        ]),
                    TextInput::make('twitter.title'),
                    Textarea::make('twitter.description'),
                ]),

            Group::make()
                ->schema([
                    Select::make('schema.type')
                        ->label('Schema Type')
                        ->options([
                            'WebApplication' => 'WebApplication',
                            'SoftwareApplication' => 'SoftwareApplication',
                        ])
                        ->default('WebApplication'),

                    CodeEditor::make('schema.json')
                        ->label('JSON-LD')
                        ->language(Language::Json)
                        ->helperText('در صورت نیاز دستی ویرایش کنید'),
                ]),
        ];

    }
}
