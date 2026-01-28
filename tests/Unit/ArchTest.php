<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;

// modules should not use other modules except 'Base' module. values are exceptions.
$baseModules = [
    'App' => [],
    'Modules\Base' => [],
    'Modules\Auth' => [],
];

$appModules = [
    'Modules\User' => ['Modules\Auth'],
];

foreach ($appModules as &$appExceptions) {
    if (is_array($appExceptions)) {
        // $appExceptions[] = 'Modules\SomeModule';
    }
}

$modules = array_merge($baseModules, $appModules);

foreach ($modules as &$moduleExceptions) {
    if (is_array($moduleExceptions)) {
        $moduleExceptions[] = 'Modules\Base';
    }
}

$moduleNames = collect($modules)->keys();
$ignoreFinalList = [];
foreach ($moduleNames as $moduleName) {
    $ignoreFinalList[] = "$moduleName\Services";
    $ignoreFinalList[] = "$moduleName\Support";
    $ignoreFinalList[] = "$moduleName\Rules";
}

arch()->preset()->php()
    ->ignoring("App\Filament"); // can use translate strings to remove this ignore
// not make services final to easy mocking for test
arch()->preset()->strict()->ignoring(array_merge($ignoreFinalList, ["App\Filament"]));
// strict ignored list
foreach ($ignoreFinalList as $directory) {
    arch()->expect($directory)->classes()->not->toBeAbstract()
        ->and($directory)->toUseStrictTypes()
        ->and($directory)->toUseStrictEquality();
}
arch()->preset()->security();

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

describe('Module isolation', function () use ($modules): void {
    foreach ($modules as $module => $exceptions) {
        if ($exceptions === 'all') {
            break;
        }
        foreach (array_keys($modules) as $otherModule) {
            if ($module === $otherModule) {
                continue;
            }
            if (in_array($otherModule, $exceptions)) {
                continue;
            }
            $ignoreList = array_merge($exceptions, [
                AppServiceProvider::class,
                $otherModule.'\Contracts',
                $otherModule.'\Services',
                $otherModule.'\Events',
                $otherModule.'\Models',
            ]);
            test("$module should not use $otherModule ignoring ".implode(', ', $ignoreList))
                ->expect($module)
                ->not->toUse($otherModule)
                ->ignoring($ignoreList);
        }
    }
});
