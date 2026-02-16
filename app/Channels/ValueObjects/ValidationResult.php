<?php

namespace App\Channels\ValueObjects;

readonly class ValidationResult
{
    public function __construct(
        public bool $valid,
        public ?string $error = null,
        public array $warnings = [],
    ) {}

    public static function passed(): self
    {
        return new self(valid: true);
    }

    public static function failed(string $error): self
    {
        return new self(valid: false, error: $error);
    }

    public static function passedWithWarnings(array $warnings): self
    {
        return new self(valid: true, warnings: $warnings);
    }
}
