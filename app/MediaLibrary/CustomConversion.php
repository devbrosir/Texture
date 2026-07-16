<?php

declare(strict_types=1);

namespace App\MediaLibrary;

class CustomConversion
{
    public ?int $width = null;

    public ?int $height = null;

    public function __construct(
        public readonly string $name,
    ) {}

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
}
