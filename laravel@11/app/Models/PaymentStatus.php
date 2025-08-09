<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentStatus extends BaseModel
{
    use HasFactory;

    protected $table = 'payment_status';

    protected $fillable = [
        'name',
        'company_id'
    ];

    public $timestamps = false;
}
