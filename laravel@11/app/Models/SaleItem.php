<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm

namespace App\Models;

use App\Common\Constants;
use App\Helpers\PriceCalculator;
use App\Helpers\VatCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends BaseModel
{
  use HasFactory;

  private static $seq = 'sale_items_id_seq';

  static $fillableColumn = [
    'sale_id',
    'item_id',
    'quantity',
    'discount_amount',
  ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'markup' => 'decimal:2',
        'percentage_discount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'whole_sale_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'final_selling_price' => 'decimal:2',
        'is_vat_enabled' => 'boolean',
        'vat_amount' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function ($saleItem) {
            $saleItem->selling_price = PriceCalculator::calculateSellingPrice($saleItem->cost_price, $saleItem->markup, $saleItem->markup_amount);
            $saleItem->discounted_price = PriceCalculator::calculateDiscountedPrice($saleItem->selling_price, $saleItem->percentage_discount, $saleItem->discount_amount);

            $vatRelated = VatCalculator::computeFinalSellingPrice($saleItem->selling_price, $saleItem->discounted_price,
                $saleItem->is_vat_enabled, Constants::VAT_PERCENTAGE);

            $saleItem->vat_amount = $vatRelated['vat_amount'];
            $saleItem->vat_percentage = Constants::VAT_PERCENTAGE;
            $saleItem->final_selling_price = $vatRelated['final_selling_price'];
            $saleItem->total_amount = $saleItem->quantity * $saleItem->final_selling_price;
        });
    }

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
}
