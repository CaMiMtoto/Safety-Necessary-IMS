<?php

namespace App\Models;

use App\Constants\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string|null $sku
 * @property string|null $description
 * @property int $category_id
 * @property string $price
 * @property int $stock_quantity
 * @property int $sold_in_square_meters
 * @property float|null $box_coverage Coverage in square meters (m²)
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $unit_measure
 * @property string|null $stock_unit_measure
 * @property int|null $reorder_level
 * @property-read \App\Models\Category $category
 * @property-read int|float $actual_qty
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleOrderItem> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $stockTransactions
 * @property-read int|null $stock_transactions_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBoxCoverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereReorderLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSoldInSquareMeters($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStockUnitMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnitMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;
    protected $appends = ['actual_qty'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SaleOrderItem::class, 'product_id');
    }

    public function stockTransactions(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(StockTransaction::class, 'reference', 'reference_type', 'reference_id');
    }

    // Method to adjust stock
    public function adjustStock($newQuantity, $note): void
    {
        // Calculate the difference between the new quantity and the current quantity
        $quantityDifference = $newQuantity - $this->stock_quantity;

        // Update the stock quantity to the new value
        $this->stock_quantity = $newQuantity;
        $this->save();

        // Log the stock adjustment in the stocks table
        $this->stockTransactions()->create([
            'product_id' => $this->id,
            'quantity' => $newQuantity,  // Store the difference for logging purposes
            'transaction_type' => TransactionType::ADJUSTMENT,
            'reason' => $note
        ]);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($product) {
            if ($product->sold_in_square_meters && !$product->box_coverage) {
                throw new \Exception("Box coverage is required for products sold in square meters.");
            }
        });
    }

    public function getActualQtyAttribute(): float|int
    {
        // if the product is sold in square meters, then the actual qty is the box coverage
        if ($this->sold_in_square_meters) {
            return $this->stock_quantity * $this->box_coverage;
        }
        return $this->stock_quantity;
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'product_id');
    }



}
