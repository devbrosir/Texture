<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->bootModelsDefaults();
        $this->bootSettings();
        $this->bootFactoryDirectoryStructure();
    }

    private function bootModelsDefaults(): void
    {
        Model::unguard();
    }

    private function bootSettings(): void
    {
        Model::shouldBeStrict(App::isLocal());
        Date::useClass(CarbonImmutable::class);
        DB::prohibitDestructiveCommands(App::isProduction());
    }

    private function bootFactoryDirectoryStructure(): void
    {
        // Say Laravel to resolve Module models to their factories
        Factory::guessFactoryNamesUsing(fn (string $modelName) =>
            // Convert: Modules\User\Models\User
            // To:      Modules\User\database\Factories\UserFactory
            str_replace(
                ['\\Models\\', '\\Models'],
                ['\\Database\\factories\\', '\\Database\\factories'],
                $modelName
            ).'Factory');

    }
}
