<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for managing inventory
// Note: You can add as many functions if needed

namespace App\Services;

use App\Enums\InventoryMovementType;
use App\Models\InventoryMovement;
use App\Models\SaleItem;

class InventoryManager {
    /**
     * @throws \Exception
     */
    public function reduceStockForSoldItems(array $items): void
    {
        foreach ($items as $item) {
            $itemResult = ItemService::showDetail($item['item_id']);

            if ($itemResult['success']) {
                $itemDb = $itemResult['data'];
                $availableStock = $itemDb->stock_quantity - $item['quantity'];

                $itemDb->stock_quantity = $availableStock;
                $itemDb->save();

                $item['available_stock'] = $availableStock;

                $this->createInventoryMovement($item, InventoryMovementType::STOCK_OUT);
            } else {
                Throw new \Exception('Item not found');
            }
        }
    }

    public function createInventoryMovement(array $item, InventoryMovementType $movementType, ?string $remarks = null): void
    {
        $itemId = $item['item_id'];
        $companyId = $item['company_id'];
        $quantity = $item['quantity'];
        $availableStock = $item['available_stock'];
        $saleItemId = null;

        if (isset($item['sale_id'])) {
          $saleItem = SaleItem::where([
              ['item_id', $itemId],
              ['sale_id', $item['sale_id']],
              ])->first();

          $saleItemId = $saleItem->id;
        }

        $inventoryMovement = new InventoryMovement();
        $inventoryMovement->item_id = $itemId;
        $inventoryMovement->company_id = $companyId;
        $inventoryMovement->sale_item_id = $saleItemId;
        $inventoryMovement->quantity = $quantity;
        $inventoryMovement->movement_type = $movementType;
        $inventoryMovement->available_stock = $availableStock;
        $inventoryMovement->remarks = $remarks;
        $inventoryMovement->save();
    }
}
