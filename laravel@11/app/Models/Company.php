<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends BaseModel
{
    use HasFactory;

    protected $table = 'company';

    protected $fillable = [
        'name',
        'abbr',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'email'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function itemTypes()
    {
        return $this->hasMany(ItemType::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function itemStatus()
    {
        return $this->hasMany(ItemStatus::class);
    }
}
