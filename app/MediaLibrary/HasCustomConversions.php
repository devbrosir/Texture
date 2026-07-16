<?php

declare(strict_types=1);

namespace App\MediaLibrary;

trait HasCustomConversions
{
    protected array $customConversions = [];

    abstract public function registerCustomConversions(): void;

    /** @return array<CustomConversion> */
    public function getCustomConversions(): array
    {
        $this->customConversions = [];

        $this->registerCustomConversions();

        return $this->customConversions;
    }

    protected function addCustomConversion(string $name): CustomConversion
    {
        return $this->customConversions[$name] = new CustomConversion($name);
    }

    protected function getCustomConversionUrl(string $collection, string $conversionName): ?string
    {
        $media = $this->getFirstMedia($collection);

        if (! $media) {
            return null;
        }

        $fileName = pathinfo($media->file_name, PATHINFO_FILENAME);

        return dirname($media->getUrl())."/conversions/$fileName-$conversionName.$media->extension";
    }
}
