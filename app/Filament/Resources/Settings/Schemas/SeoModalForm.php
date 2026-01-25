<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;

class SeoModalForm
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
//                ->label('Open Graph')
                ->schema([
                    TextInput::make('og.title')->label('OG Title'),
                    Textarea::make('og.description')->label('OG Description'),
                    TextInput::make('og.image')->label('OG Image URL'),
                ]),

            Group::make()
//                ->label('Twitter Card')
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
//                ->label('Structured Data (JSON-LD)')
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
