<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends BaseModel
{
    use HasFactory;

    private static $seq = 'sales_id_seq';

    static $fillableColumn = [
      'customer_id',
      'user_id',
      'company_id',
      'total_amount',
      'status',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    static function getLastSequenceId() {
      return parent::getCurrentSequence(self::$seq);
    }

    static function resetLastSequenceId($val)
    {
      parent::resetSequence(self::$seq, $val);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'sale_id');
    }
}
