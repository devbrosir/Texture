<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\GenerateCustomMediaConversion;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Providers\AuthModuleServiceProvider;
use Modules\Base\Providers\BaseModuleServiceProvider;
use Modules\Sms\Providers\SmsModuleServiceProvider;
use Modules\Upload\Providers\UploadModuleServiceProvider;
use Modules\User\Providers\UserModuleServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

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
        Event::listen(MediaHasBeenAddedEvent::class, GenerateCustomMediaConversion::class);
    }

    private function bootModelsDefaults(): void
    {
        Model::unguard();
    }

    private function bootSettings(): void
    {
        Model::shouldBeStrict(App::isLocal());
        Date::useClass(CarbonImmutable::class);
        URL::forceScheme(app()->isLocal() ? 'http' : 'https');
    }
}
