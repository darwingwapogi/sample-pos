<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends BaseModel
{
    use HasFactory;

    protected $table = 'units';

    static $fillableColumn = [
        'name',
        'symbol',
        'company_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
