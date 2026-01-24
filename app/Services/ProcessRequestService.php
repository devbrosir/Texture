<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProcessRequest;
use Illuminate\Support\Facades\DB;
use Modules\Upload\Services\FileService;

class ProcessRequestService
{
    public function store(array $fields, array $images): ProcessRequest
    {
        $fields['user_id'] = auth()->id();
        DB::transaction(function () use (&$processRequest, $fields, $images): void {
            $processRequest = ProcessRequest::query()->create($fields);
            new FileService()->assignFiles($images, $processRequest, ProcessRequest::IMAGES);
        });

        return $processRequest->refresh()->append('images');
    }
}
