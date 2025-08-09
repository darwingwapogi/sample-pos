<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemStatus extends BaseModel
{
    use HasFactory;

    protected $table = 'item_status';

    protected $fillable = [
        'name',
        'company_id',
    ];

    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(Item::class, 'status_id');
    }
}
