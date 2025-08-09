<?php

namespace App\Services;

use App\Common\Utility\ArrayUtil;
use App\Enums\InventoryMovementType;
use App\Models\Category;
use App\Models\Company;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class ItemService extends BaseService {

    /**
     * @throws \Exception
     */
    public static function createOrUpdate($data): array
  {
    $id = ArrayUtil::valueAt($data, 'id');

    $modelData = [];

    foreach (Item::$fillableColumn as $column) {
      if (!isset($data[$column])) continue;

      $modelData[$column] = ArrayUtil::valueAt($data, $column);
    }

    if (isset($id)) {
        unset($modelData['stock_quantity']);

        return parent::update($modelData, $id, new Item);
    } else {
        try {
          $result = [
              'success' => false
          ];

          $itemResult = parent::create($modelData, new Item, true);
          if ($itemResult['success']) {
              $itemData = $itemResult['created_data'];
              $inventoryMovement = new InventoryMovement();
              $inventoryMovement->item_id = $itemData->id;
              $inventoryMovement->company_id = $itemData->company_id;
              $inventoryMovement->quantity = $itemData->stock_quantity;
              $inventoryMovement->movement_type = InventoryMovementType::STOCK_IN;
              $inventoryMovement->available_stock = $itemData->stock_quantity;
              $inventoryMovement->save();

              $result['success'] = true;
              $result['message'] = 'Successfully created.';
          }
        } catch (\Exception $e) {
            DB::rollBack();
            self::logError($e);
            return [
                'success' => false,
                'error' => 'Failed to create item.'
            ];
        }

        return $result;
    }
  }

  public static function list($data): array
  {
    $companyId = ArrayUtil::valueAt($data, 'company_id');
    $list = Item::where('items.company_id', $companyId)
                ->join('categories', 'items.category_id', '=', 'categories.id')
                ->join('units', 'items.unit_id', '=', 'units.id')
                ->join('item_types', 'items.item_type_id', '=', 'item_types.id')
                ->join('suppliers', 'items.supplier_id', '=', 'suppliers.id')
                ->join('item_status', 'items.status_id', '=', 'item_status.id')
                ->with(['category', 'unit', 'itemType', 'supplier', 'status']) // Eager load all related models
                ->select('items.*');

    /*
      Special case: if the column/s that you are searching is/are from another table.
      You need to specify the exact column name from the table.
      `Table name`.'Column name`
      Ex: category.name
    */
    if (isset($data['searchForm'])) {
      $searchableFields = [
        'category' => ['category_id', false],
        'item_type' => ['item_type_id', false],
        'supplier' => ['suppliers.name', true],
        'name' => ['items.name', true],
        'brand' => ['brand', true],
        'model' => ['model', true],
        'size' => ['size', true],
        'color' => ['color', true],
        'final_selling_price' => ['final_selling_price', true],
        'stock_quantity' => ['stock_quantity', true],
      ];

      foreach($data['searchForm'] as $field => $value) {
        if (isset($searchableFields[$field])) {
          $column = $searchableFields[$field][0];
          $isWildcard = $searchableFields[$field][1];

          if ($isWildcard) {
            $list->whereRaw('LOWER(' . $column . ') LIKE ?', ['%' . strtolower($value) . '%']);
          } else {
            $list->where($column, $value);
          }
        }
      }
    }

    if (isset($data['sortField'])) {
        // Check if the sortField is in the sortable fields
        // and set the default sort order to 'asc' if not provided
        $sortField = $data['sortField'] ?? 'name';
        $sortOrder = strtoupper($data['sortOrder']);

        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            // If the sortOrder is not valid, set it to 'ASC'
            $sortOrder = 'ASC';
        }

        $sortableFields = [
            'category' => 'categories.name',
            'item_type' => 'item_types.name',
            'supplier' => 'suppliers.name',
            'status' => 'item_status.name',
            'name' => 'items.name',
            'brand' => 'brand',
            'model' => 'model',
            'size' => 'size',
            'color' => 'color',
            'final_selling_price' => 'final_selling_price',
            'stock_quantity' => 'stock_quantity',
        ];

        if (!array_key_exists($sortField, $sortableFields)) {
            // If the sortField is not in the sortable fields, set it to a default value
            $sortField = 'name';
        }

        $list->orderBy($sortableFields[$sortField], $sortOrder);
    }

    return parent::datatableList($data, $list);
  }

  public static function showDetail($id): array
  {
    return parent::find($id, new Item);
  }

  public static function remove($id)
  {
    return parent::delete($id, new Item);
  }

  public static function generateSku($modelData): string
  {
    $companyAbbr = self::getCompanyAbbr($modelData['company_id']);
    $category = self::getCategory($modelData['category_id']);
    $unit = self::getUnit($modelData['unit_id']);
    $itemName = $modelData['name'];
    $size = $modelData['size'];
    $color = $modelData['color'];
    $supplierId = $modelData['supplier_id'];

    return Item::generateSku($companyAbbr, $itemName, $category, $size, $unit, $color, $supplierId);
  }

    /**
     * @throws \Exception
     */
    public static function updateStock(array $item): array
    {
      try {
          $id = $item['id'];
          $quantity = $item['quantity'];
          $companyId = $item['company_id'];
          $remarks = $item['remarks'] ?? null;
          $movementType = $item['movement_type'];

          $itemDB = Item::find($id);

          if (!$itemDB)
              return [
                  'success' => false,
                  'error' => 'Item not found.'
              ];

          $movementTypeEnum = InventoryMovementType::from($movementType);

          if (in_array($movementTypeEnum,
              [
                  InventoryMovementType::STOCK_OUT,
                  InventoryMovementType::ADJUSTMENT_DECREASE,
                  InventoryMovementType::RETURN,
                  InventoryMovementType::TRANSFER,
                  InventoryMovementType::EXPIRED,
                  InventoryMovementType::DAMAGED
              ])
          ) {
              $itemDB->stock_quantity = max($itemDB->stock_quantity - $quantity, 0);

          } elseif (in_array($movementTypeEnum,
              [
                  InventoryMovementType::STOCK_IN,
                  InventoryMovementType::ADJUSTMENT_INCREASE
              ])
          ) {
              $itemDB->stock_quantity += $quantity;
          }

          $itemDB->save();

          $inventoryMovement = new InventoryMovement();
          $inventoryMovement->item_id = $id;
          $inventoryMovement->company_id = $companyId;
          $inventoryMovement->quantity = $quantity;
          $inventoryMovement->movement_type = $movementType;
          $inventoryMovement->available_stock = $itemDB->stock_quantity;
          $inventoryMovement->remarks = $remarks;
          $inventoryMovement->save();

          return [
              'success' => true,
              'message' => 'Stock updated successfully.'
          ];
      } catch (\Exception $e) {
          DB::rollBack();
          self::logError($e);

          return [
              'success' => false,
              'error' => 'Failed to update stock.'
          ];
      }
  }

  private static function getCompanyAbbr($companyId): string
  {
    $company = Company::find($companyId);
    return $company ? $company->abbr : '';
  }

  private static function getCategory($categoryId): string
  {
    $category = Category::find($categoryId);
    return $category ? $category->name : '';
  }

  private static function getUnit($unitId): string
  {
    $unit = Unit::find($unitId);
    return $unit ? $unit->name : '';
  }
}
