<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Providers\AuthModuleServiceProvider;
use Modules\Base\Providers\BaseModuleServiceProvider;
use Modules\Sms\Providers\SmsModuleServiceProvider;
use Modules\Upload\Providers\UploadModuleServiceProvider;
use Modules\User\Providers\UserModuleServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(BaseModuleServiceProvider::class);
        $this->app->register(AuthModuleServiceProvider::class);
        $this->app->register(UserModuleServiceProvider::class);
        $this->app->register(UploadModuleServiceProvider::class);
        $this->app->register(SmsModuleServiceProvider::class);
    }

    public function boot(): void
    {
        $this->bootModelsDefaults();
        $this->bootSettings();
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
}
