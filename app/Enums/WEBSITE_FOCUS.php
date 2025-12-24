<?php

namespace App\Enums;

enum WEBSITE_FOCUS: string
{
    case DAILY_RENT = 'DAILY_RENT';
    case RENT = 'RENT';
    case SELLING = 'SELLING';
    case ALL = 'ALL';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromString(string $value): self
    {
        return match ($value) {
            'DAILY_RENT' => self::DAILY_RENT,
            'RENT' => self::RENT,
            'SELLING' => self::SELLING,
            'ALL' => self::ALL,
            default => self::ALL,
        };
    }
} 