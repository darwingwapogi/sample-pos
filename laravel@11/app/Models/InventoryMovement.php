<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryMovement extends BaseModel
{
    use HasFactory;

    protected array $fillableColumn = [
        'item_id',
        'company_id',
        'sale_item_id',
        'quantity',
        'movement_type',
        'remarks',
    ];

    protected $guarded = [];

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
