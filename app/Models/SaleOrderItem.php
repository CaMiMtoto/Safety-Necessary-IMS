<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $sale_order_id
 * @property int $product_id
 * @property int $quantity
 * @property string $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $delivered
 * @property-read mixed $remaining
 * @property-read int|float $total
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleDeliveryItem> $saleDeliveryItems
 * @property-read int|null $sale_delivery_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $stockTransactions
 * @property-read int|null $stock_transactions_count
 * @method static Builder<static>|SaleOrderItem newModelQuery()
 * @method static Builder<static>|SaleOrderItem newQuery()
 * @method static Builder<static>|SaleOrderItem query()
 * @method static Builder<static>|SaleOrderItem whereCreatedAt($value)
 * @method static Builder<static>|SaleOrderItem whereId($value)
 * @method static Builder<static>|SaleOrderItem wherePrice($value)
 * @method static Builder<static>|SaleOrderItem whereProductId($value)
 * @method static Builder<static>|SaleOrderItem whereQuantity($value)
 * @method static Builder<static>|SaleOrderItem whereSaleOrderId($value)
 * @method static Builder<static>|SaleOrderItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SaleOrderItem extends Model
{
    use HasFactory;
    protected $appends = ['total', 'delivered', 'remaining'];

    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function getTotalAttribute(): float|int
    {
        return $this->quantity * $this->price;
    }
    public function stockTransactions(): MorphMany
    {
        return $this->morphMany(StockTransaction::class, 'reference','reference_type','reference_id');
    }

    public function saleDeliveryItems(): HasMany
    {
        return $this->hasMany(SaleDeliveryItem::class);
    }

    public function getDeliveredAttribute()
    {
        return $this->saleDeliveryItems->sum('quantity');
    }

    public function getRemainingAttribute()
    {
        return $this->quantity - $this->delivered;
    }

}
