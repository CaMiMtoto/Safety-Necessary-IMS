<?php

namespace App\Models;

use App\Traits\HasStatusColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class SalePayment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasStatusColor;
    use HasFactory;

    protected $casts = [
        'payment_date' => 'date',
    ];

    protected $appends = [
        'attachment_url',
        'status_color',
    ];
    const ATTACHMENT_PATH = 'attachments/sales/payments/';

    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getAttachmentUrlAttribute(): string
    {
        return Storage::url(self::ATTACHMENT_PATH . $this->attachment);
    }

    public function getStatusAttribute(): string
    {
        return ucfirst($this->attributes['status']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
