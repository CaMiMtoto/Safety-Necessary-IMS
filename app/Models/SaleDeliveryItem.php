<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $sale_delivery_id
 * @property int $product_id
 * @property int $sale_order_item_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $remaining
 * @property-read int $items_to_deliver
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\SaleDelivery $saleDelivery
 * @property-read \App\Models\SaleOrderItem $saleOrderItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereSaleDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereSaleOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleDeliveryItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SaleDeliveryItem extends Model
{
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function saleDelivery(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SaleDelivery::class);
    }



    public function saleOrderItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SaleOrderItem::class);
    }

    public function getItemsToDeliverAttribute(): int
    {
        return $this->quantity + $this->remaining;
    }

    public function getBoxes($qty): float|int
    {
        info("Quantity: $qty");
        $product = $this->product;
        info("Product: ".json_encode($product));
        $box_coverage = $product->box_coverage;
        info("Box coverage: $box_coverage");
        $f = $qty / $box_coverage;
        info("Box coverage: $f");
        return $f;
    }


}
