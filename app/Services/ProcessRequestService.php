<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProcessRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProcessRequestService
{
    public function store(array $fields, UploadedFile $image): ProcessRequest
    {
        $fields['user_id'] = auth()->id();
        DB::transaction(function () use (&$processRequest, $fields, $image): void {
            $processRequest = ProcessRequest::query()->create($fields);
            $processRequest->addMedia($image)->toMediaCollection(ProcessRequest::IMAGE);
        });

        return $processRequest->refresh()->append('image');
    }
}
