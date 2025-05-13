<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property string $delivery_date
 * @property string|null $delivery_address
 * @property string $delivery_status
 * @property string|null $delivered_by
 * @property int|null $sale_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $done_by
 * @property-read \App\Models\User|null $doneBy
 * @property-read mixed $delivered
 * @property-read mixed $remaining
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleDeliveryItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\SaleOrder|null $saleOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereDeliveredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereDeliveryAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereDoneBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereSaleOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDelivery whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SaleDelivery extends Model
{
    protected $appends = ['delivered', 'remaining'];
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SaleDeliveryItem::class);
    }


    public function getDeliveredAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function saleOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function getRemainingAttribute()
    {
        return  $this->delivered - $this->saleOrder->items->sum('remaining');
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'done_by');
    }

}
