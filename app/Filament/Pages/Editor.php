<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Editor extends Page
{
    protected string $view = 'filament.pages.editor';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
