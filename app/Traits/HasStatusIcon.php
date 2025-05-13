<?php

namespace App\Traits;

use App\Constants\Status;

trait HasStatusIcon
{
    public function getStatusIconFromTrait(): string
    {
        $status = strtolower($this->status);

        return match ($status) {
            strtolower(Status::UnderMaintenance),
            strtolower(Status::Draft) => 'warning',

            strtolower(Status::Submitted),
            strtolower(Status::InProgress),
            strtolower(Status::Confirmed) => 'info-circle',

            strtolower(Status::Available),
            'active',
            strtolower(Status::Completed),
            strtolower(Status::Approved) => 'check',
            strtolower(Status::Scheduled) => 'clock',

            strtolower(Status::Rejected),
            strtolower(Status::Cancelled),
            strtolower(Status::OutOfService),
            'inactive' => 'exclamation-circle',

            default => 'question-circle',
        };
    }

    // Keep the original method (optional)
    public function getStatusIconAttribute(): string
    {
        return $this->getStatusIconFromTrait();
    }
}
