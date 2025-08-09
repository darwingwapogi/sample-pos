<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends BaseModel
{
    use HasFactory;

    private static $seq = 'invoice_items_id_seq';

    static $fillableColumn = [
        'invoice_id',
        'item_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'item_id',
        'quantity',
        'price',
        'subtotal',
    ];

    static function getLastSequenceId() {
        return parent::getCurrentSequence(self::$seq);
    }

    static function resetLastSequenceId($val)
    {
        parent::resetSequence(self::$seq, $val);
    }
}
