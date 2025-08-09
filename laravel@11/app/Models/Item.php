<?php

namespace App\Models;

use App\Common\Constants;
use App\Helpers\PriceCalculator;
use App\Helpers\VatCalculator;
use App\Services\ItemService;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends BaseModel
{
  use HasFactory;

  private static $seq = 'items_id_seq';

  static array $fillableColumn = [
      'name',
      'brand',
      'model',
      'description',
      'stock_quantity',
      'sku',
      'barcode',
      'size',
      'color',
      'reorder_level',
      'cost_price',
      'markup',
      'markup_amount',
      'percentage_discount',
      'discount_amount',
      'whole_sale_price',
      'is_vat_enabled',
      'expiry_date',
      'category_id',
      'unit_id',
      'company_id',
      'item_type_id',
      'supplier_id',
      'status_id',
  ];

    protected $casts = [
        'name' => 'string',
        'brand' => 'string',
        'model' => 'string',
        'description' => 'string',
        'stock_quantity' => 'integer',
        'sku' => 'string',
        'barcode' => 'string',
        'size' => 'string',
        'color' => 'string',
        'reorder_level' => 'decimal:2',
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
        'expiry_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function ($item) {
            $item->sku = ItemService::generateSku($item);

            $item->selling_price = PriceCalculator::calculateSellingPrice($item->cost_price, $item->markup, $item->markup_amount);
            $item->discounted_price = PriceCalculator::calculateDiscountedPrice($item->selling_price, $item->percentage_discount, $item->discount_amount);

            $vatRelated = VatCalculator::computeFinalSellingPrice($item->selling_price, $item->discounted_price,
                                                        $item->is_vat_enabled, Constants::VAT_PERCENTAGE);

            $item->vat_amount = $vatRelated['vat_amount'];
            $item->vat_percentage = Constants::VAT_PERCENTAGE;
            $item->final_selling_price = $vatRelated['final_selling_price'];
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

  /**
   * Generate SKU based on the given format.
   */
  static function generateSku($companyAbbr, $itemName, $category, $size, $unit, $color, $supplierId): string
  {
      $parts = [
          substr($companyAbbr, 0, 3),
          substr($itemName, 0, 3),
          substr($category, 0, 3),
          substr($size, 0, 3),
          substr($unit, 0, 3),
          substr($color, 0, 3),
          str_pad($supplierId, 3, '0', STR_PAD_LEFT)
      ];

      $filteredParts = array_filter($parts, function($part) {
          return !empty($part);
      });

      return strtoupper(implode('-', $filteredParts));
  }

  public function category()
  {
      return $this->belongsTo(Category::class);
  }

  public function unit()
  {
      return $this->belongsTo(Unit::class);
  }

  public function company()
  {
      return $this->belongsTo(Company::class);
  }

  public function itemType()
  {
      return $this->belongsTo(ItemType::class, 'item_type_id');
  }

  public function supplier()
  {
      return $this->belongsTo(Supplier::class);
  }

  public function status()
  {
      return $this->belongsTo(ItemStatus::class, 'status_id');
  }

  public function saleItem()
  {
      return $this->hasMany(SaleItem::class, 'item_id');
  }
}
