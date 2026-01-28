<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $title
 * @property-read string $key
 * @property-read bool $show
 * @property-read array $value
 */
final class Setting extends BaseModel implements HasMedia
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    use InteractsWithMedia;

    public static function updateValue(array $data, string $key): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            [
                'key' => $key,
                'show' => false,
                'value' => json_encode($data),
            ]);
    }

    public static function get(string $key): array
    {
        return self::query()->where('key', $key)->first()?->value ?? [];
    }

    public function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->decodeIfJson($value),
            set: fn ($value): mixed => $this->encodeIfArray($value),
        );
    }

    private function decodeIfJson($value)
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    private function encodeIfArray($value): mixed
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return $value;
    }
}
