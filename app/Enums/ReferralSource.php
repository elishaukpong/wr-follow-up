<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReferralSource: string implements HasLabel, HasColor
{
    case Friend = 'friend';
    case Family = 'family';
    case SocialMedia = 'social_media';
    case Flyer = 'flyer';
    case Website = 'website';
    case PassingBy = 'passing_by';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Friend => 'Friend/Colleague',
            self::Family => 'Family Member',
            self::SocialMedia => 'Social Media',
            self::Flyer => 'Flyer/Poster',
            self::Website => 'Website',
            self::PassingBy => 'Passing By',
            self::Other => 'Other',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Friend => 'success',
            self::Family => 'primary',
            self::SocialMedia => 'info',
            self::Flyer => 'warning',
            self::Website => 'gray',
            self::PassingBy => 'danger',
            self::Other => 'gray',
        };
    }
}
