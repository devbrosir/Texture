<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ActivityType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

final class BatchStoreActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'activities' => ['required', 'array'],
            'activities.*' => ['required', 'array'],
            'activities.*.created_at' => ['required', 'date'],
            'activities.*.type' => ['required', new In(ActivityType::values())],
            'activities.*.related_id' => ['nullable', 'integer'],
            'activities.*.related_type' => ['nullable', 'string', 'max:255'],
            'activities.*.metadata' => ['nullable', 'array'],
        ];
    }
}
