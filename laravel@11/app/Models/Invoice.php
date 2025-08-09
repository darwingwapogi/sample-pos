<?php
// Author: DarCasanova
// Date: 03/12/2025
// Time: 9:01am

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends BaseModel
{
    use HasFactory;

    private static $seq = 'invoices_id_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'customer_id',
        'company_id',
        'due_date',
        'status_id',
        'total_amount'
    ];
    
    static $fillableColumn = [
      'sale_id',
      'customer_id',
      'company_id',
      'due_date',
      'status_id',
      'total_amount'
    ];

    static function getLastSequenceId() {
      return parent::getCurrentSequence(self::$seq);
    }

    static function resetLastSequenceId($val)
    {
      parent::resetSequence(self::$seq, $val);
    }
}
