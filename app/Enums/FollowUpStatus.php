<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum FollowUpStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Contacted = 'contacted';
    case Connected = 'connected';
    case NoResponse = 'no_response';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Contacted => 'Contacted',
            self::Connected => 'Connected',
            self::NoResponse => 'No Response',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Contacted => 'info',
            self::Connected => 'success',
            self::NoResponse => 'danger',
        };
    }
}
