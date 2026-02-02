<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BatchStoreActivityRequest;
use App\Services\ActivityService;

final class ActivityController
{
    public function batchStore(BatchStoreActivityRequest $request, ActivityService $service): void
    {
        $service->add($request->array('activities'));
    }
}
