<?php

namespace App\Constants;

class Status
{
    const ORDER = "Order";
    const PARTIALLY_PAID = "Partially Paid";
    const PAID = "Paid";
    const PARTIALLY_DELIVERED = "Partially Delivered";
    const DELIVERED = "Delivered";
    const CANCELLED = "Cancelled";
    const UNPAID = "Unpaid";

    public static function getStatuses(): array
    {
        return [
            self::ORDER,
            self::PARTIALLY_DELIVERED,
            self::DELIVERED,
            self::CANCELLED
        ];
    }

    public static function getPaymentStatuses(): array
    {
        return [
            self::PAID,
            self::CANCELLED,
        ];
    }

}
