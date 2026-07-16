<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;
use Imagick;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ConversionGenerator
{
    protected ?string $conversion;

    protected ?int $width = null;

    protected ?int $height = null;

    public function conversion(string $name): static
    {
        $this->conversion = $name;

        return $this;
    }

    public function width(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function height(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function generate(Media $media): void
    {
        $source = $media->getPath();

        $destination = $this->destinationPath($media);

        if (! is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        $image = new Imagick($source);
        $this->resize($image);
        $image->setImageCompressionQuality(100);
        $image->writeImage($destination);
        $image->clear();
    }

    protected function resize(Imagick $image): void
    {
        $image->resizeImage($this->width ?? 0, $this->height ?? 0, Imagick::FILTER_SINC, 1, true);
    }

    protected function destinationPath(Media $media): string
    {
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
        $fileName = basename($media->file_name);
        $fileName = Str::before($fileName, '.'.$extension);

        return dirname($media->getPath())."/conversions/$fileName-$this->conversion.$extension";
    }
}
