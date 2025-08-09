<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 8:55am

namespace App\Models;

class InvoiceStatus extends BaseModel
{
    protected $table = 'invoice_status';

    protected $fillable = [
        'name',
        'company_id',
    ];

    public $timestamps = false;
}
