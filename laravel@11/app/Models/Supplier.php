<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\SupplierStatus;

class Supplier extends BaseModel
{
    use HasFactory;

    protected $table = 'suppliers';

    protected array $fillableColumn = [
        'company_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'province',
        'country',
        'zip_code',
        'status',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    protected $casts = [
        'status' => SupplierStatus::class,
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
