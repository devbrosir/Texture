<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Enums\RequestStatus;
use App\Facades\ActivityLogger;
use App\Http\Requests\StoreProcessRequestRequest;
use App\Models\ProcessRequest;
use App\Services\ProcessRequestService;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProcessRequestController
{
    public function index(): LengthAwarePaginator
    {
        return ProcessRequest::query()->where('user_id', auth()->id())->latest()->paginate(50);
    }

    public function store(StoreProcessRequestRequest $request, ProcessRequestService $service): ProcessRequest
    {
        return $service->store($request->safe()->except('images'), $request->array('images'));
    }

    public function cancel(ProcessRequest $processRequest): void
    {
        abort_unless($processRequest->ownedBy(), 404);
        $processRequest->update(['status' => RequestStatus::CANCELED]);
        ActivityLogger::log(ActivityType::CANCEL_REQUEST, $processRequest);
    }
}
