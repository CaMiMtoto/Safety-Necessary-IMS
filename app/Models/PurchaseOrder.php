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
use OwenIt\Auditing\Contracts\Auditable;

/**
 *
 *
 * @property int $id
 * @property string|null $invoice_number
 * @property int|null $supplier_id
 * @property string $status
 * @property Carbon|null $delivery_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $done_by
 * @property-read \App\Models\User|null $doneBy
 * @property-read mixed $total
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseOrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Database\Factories\PurchaseOrderFactory factory($count = null, $state = [])
 * @method static Builder<static>|PurchaseOrder newModelQuery()
 * @method static Builder<static>|PurchaseOrder newQuery()
 * @method static Builder<static>|PurchaseOrder query()
 * @method static Builder<static>|PurchaseOrder whereCreatedAt($value)
 * @method static Builder<static>|PurchaseOrder whereDeliveryDate($value)
 * @method static Builder<static>|PurchaseOrder whereDoneBy($value)
 * @method static Builder<static>|PurchaseOrder whereId($value)
 * @method static Builder<static>|PurchaseOrder whereInvoiceNumber($value)
 * @method static Builder<static>|PurchaseOrder whereStatus($value)
 * @method static Builder<static>|PurchaseOrder whereSupplierId($value)
 * @method static Builder<static>|PurchaseOrder whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PurchaseOrder extends Model implements Auditable
{
    use HasFactory,\OwenIt\Auditing\Auditable;
    protected $casts = [
        'delivery_date' => 'date',
    ];
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function generateInvoiceNumber(): string
    {
        $str = $this->id;
        $padded = str_pad($str, 5, '0', STR_PAD_LEFT);
        $invNo = 'VN-' . $padded;
        return $this->update(['invoice_number' => $invNo]);
    }

    public function getTotalAttribute()
    {
        return $this->items->sum('total');
    }


    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'done_by');
    }



}
