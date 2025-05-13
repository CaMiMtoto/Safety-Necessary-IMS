<?php

namespace App\Traits;

use App\Constants\Status;

trait HasStatusColor
{
    public function getStatusColorFromTrait(): string
    {
        $status = strtolower($this->status);

        return match ($status) {
            strtolower(Status::ORDER) => 'warning',
            strtolower(Status::PARTIALLY_PAID) => 'primary',

            strtolower(Status::PAID),
            strtolower(Status::PARTIALLY_DELIVERED) => 'info',

            strtolower(Status::DELIVERED) => 'success',

            strtolower(Status::CANCELLED) => 'danger',

            default => 'secondary',
        };
    }

    // Keep the original method (optional)
    public function getStatusColorAttribute(): string
    {
        return $this->getStatusColorFromTrait();
    }
}
