<?php

namespace App\Models;

use App\Traits\HasStatusColor;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 *
 *
 * @property int $id
 * @property int $customer_id
 * @property string $total_amount
 * @property string $status
 * @property Carbon $order_date
 * @property string|null $invoice_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $done_by
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleDelivery> $deliveries
 * @property-read int|null $deliveries_count
 * @property-read \App\Models\User|null $doneBy
 * @property-read string $status_color
 * @property-read mixed $total
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleOrderItem> $items
 * @property-read int|null $items_count
 * @method static Builder<static>|SaleOrder newModelQuery()
 * @method static Builder<static>|SaleOrder newQuery()
 * @method static Builder<static>|SaleOrder query()
 * @method static Builder<static>|SaleOrder whereCreatedAt($value)
 * @method static Builder<static>|SaleOrder whereCustomerId($value)
 * @method static Builder<static>|SaleOrder whereDoneBy($value)
 * @method static Builder<static>|SaleOrder whereId($value)
 * @method static Builder<static>|SaleOrder whereInvoiceNumber($value)
 * @method static Builder<static>|SaleOrder whereOrderDate($value)
 * @method static Builder<static>|SaleOrder whereStatus($value)
 * @method static Builder<static>|SaleOrder whereTotalAmount($value)
 * @method static Builder<static>|SaleOrder whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SaleOrder extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use HasStatusColor;

    protected $appends = ['status_color'];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleOrderItem::class);
    }

    public function generateInvoiceNumber(): bool
    {
        $str = $this->id;
        $padded = str_pad($str, 5, '0', STR_PAD_LEFT);
        $invNo = 'SO-' . $padded;
        return $this->update(['invoice_number' => $invNo]);
    }

    public function getTotalAttribute()
    {
        return $this->items->sum('total');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(SaleDelivery::class);
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'done_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class, 'sale_order_id');
    }


}
