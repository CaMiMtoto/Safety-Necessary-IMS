<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property int $product_id
 * @property int $quantity
 * @property string $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|float $total
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $stockTransactions
 * @property-read int|null $stock_transactions_count
 * @method static \Database\Factories\PurchaseOrderItemFactory factory($count = null, $state = [])
 * @method static Builder<static>|PurchaseOrderItem newModelQuery()
 * @method static Builder<static>|PurchaseOrderItem newQuery()
 * @method static Builder<static>|PurchaseOrderItem query()
 * @method static Builder<static>|PurchaseOrderItem whereCreatedAt($value)
 * @method static Builder<static>|PurchaseOrderItem whereId($value)
 * @method static Builder<static>|PurchaseOrderItem wherePrice($value)
 * @method static Builder<static>|PurchaseOrderItem whereProductId($value)
 * @method static Builder<static>|PurchaseOrderItem wherePurchaseOrderId($value)
 * @method static Builder<static>|PurchaseOrderItem whereQuantity($value)
 * @method static Builder<static>|PurchaseOrderItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $appends = ['total'];
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

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }



}
