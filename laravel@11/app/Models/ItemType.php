<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemType extends BaseModel
{
    use HasFactory;

    static $fillableColumn = [
        'name',
        'description',
        'company_id',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_type_id');
    }
}
