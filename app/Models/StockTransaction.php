<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string $transaction_type
 * @property int $reference_id
 * @property string $reference_type
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 * @property-read Model|Eloquent $reference
 * @method static Builder<static>|StockTransaction newModelQuery()
 * @method static Builder<static>|StockTransaction newQuery()
 * @method static Builder<static>|StockTransaction query()
 * @method static Builder<static>|StockTransaction whereCreatedAt($value)
 * @method static Builder<static>|StockTransaction whereId($value)
 * @method static Builder<static>|StockTransaction whereProductId($value)
 * @method static Builder<static>|StockTransaction whereQuantity($value)
 * @method static Builder<static>|StockTransaction whereReason($value)
 * @method static Builder<static>|StockTransaction whereReferenceId($value)
 * @method static Builder<static>|StockTransaction whereReferenceType($value)
 * @method static Builder<static>|StockTransaction whereTransactionType($value)
 * @method static Builder<static>|StockTransaction whereUpdatedAt($value)
 * @mixin Eloquent
 */
class StockTransaction extends Model
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
