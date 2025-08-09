<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends BaseModel
{
    use HasFactory;

    private static string $seq = 'payments_id_seq';

    protected $table = 'payments';

    static $fillableColumn = [
      'sale_id',
      'amount',
      'method_id',
      'status_id',
      'transaction_id',
      'transaction_type_id',
      'notes',
      'paid_at',
    ];

    protected $guarded = [];

    protected $casts = [
      'status_id' => 'integer', // Store as integer
      'transaction_type_id' => 'integer',
      'paid_at' => 'datetime',
    ];

    public $timestamps = false;

    // Accessor to get the enum
    public function getStatusAttribute(): PaymentStatus {
      return PaymentStatus::from($this->status_id);
    }

    static function getLastSequenceId() {
      return parent::getCurrentSequence(self::$seq);
    }

    static function resetLastSequenceId($val)
    {
      parent::resetSequence(self::$seq, $val);
    }

    // Method to create a transaction ID
    public static function createTransactionId($index, $prefix = 'SALE'): string {
        // Get current datetime
        $now = new DateTime();

        // Extract date (YYYYMMDD)
        $datePart = $now->format('Ymd');

        // Extract time (HHMMSS)
        $timePart = $now->format('His');

        return $prefix . "-TXN-" . $datePart . "-" . $timePart . "-" . str_pad($index, 9, '0', STR_PAD_LEFT);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
