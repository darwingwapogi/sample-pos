<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: the core logic of POS
// Note: You can add as many functions if needed

namespace App\Services;

use App\Common\CurrentUser;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\SaleService;
use App\Services\PaymentService;
use App\Services\InventoryManager;
use App\Services\InvoiceService;
use Exception;
use Illuminate\Support\Facades\DB;

class POSManager {

  public function __construct(
    protected SaleService $saleService,
    protected SaleItemService $saleItemService,
    protected PaymentService $paymentService,
    protected InventoryManager $inventoryManager,
    protected InvoiceService $invoiceService
  ) {}

  public function processSale(array $saleData, bool $generateInvoice = false): array
  {

    if (env('DB_CONNECTION') == 'postgres') {
        // Set the sequence id to the current value
        $currentSaleSeqId = Sale::getLastSequenceId();
        $currentSaleItemSeqId = SaleItem::getLastSequenceId();
        $currentPaymentSeqId = Payment::getLastSequenceId();
        $currentInvoiceSeqId = Invoice::getLastSequenceId();
    }

    DB::beginTransaction();

    // Add current user company id to sale data
    $currentUser = new CurrentUser();
    $companyId = $currentUser->getCompanyId();

    $saleData['company_id'] = $companyId;

    try {
      // 1. Create the sale
      $saleResult = $this->saleService->createSale($saleData, true);
      if (!$saleResult['success']) throw new Exception($saleResult['errors']);

      $sale = $saleResult['created_data'];

      // 2. Add items to sale_items
      $saleItemArray = [];
      foreach ($saleData['items'] as $item) {
        $saleItemData = [];

        $itemDb = Item::find($item['id']);

        $saleItemData['sale_id'] = $sale['id'];
        $saleItemData['item_id'] = $item['id'];
        $saleItemData['quantity'] = $item['quantity'];
        $saleItemData['cost_price'] = $itemDb['cost_price'];
        $saleItemData['markup'] = $itemDb['markup'];
        $saleItemData['markup_amount'] = $itemDb['markup_amount'];
        $saleItemData['percentage_discount'] = $item['percentage_discount'] > 0 ? $item['percentage_discount'] : $itemDb['percentage_discount'];
        $saleItemData['discount_amount'] = $item['discount_amount'] > 0 ? $item['discount_amount'] : $itemDb['discount_amount'];
        $saleItemData['whole_sale_price'] = $itemDb['whole_sale_price'];
        $saleItemData['is_vat_enabled'] = $itemDb['is_vat_enabled'];
        $saleItemArray[] = $saleItemData;
      }

      $this->saleItemService->createSaleItems($saleItemArray);

      // 3. Process payment
      $payment = $this->paymentService->handlePayment($sale, $saleData);

      // 4. Update inventory
      $this->inventoryManager->reduceStockForSoldItems($saleItemArray);

      // 5. Optionally generate an invoice for credit sales
      $invoice = null;
      if ($generateInvoice) {
          $invoiceArray = [
              'sale_id' => $sale['id'],
              'customer_id' => $sale['customer_id'],
              'company_id' => $companyId,
              'due_date' => $saleData['due_date'],
              'total_amount' => $sale['total_amount']
          ];

          $invoice = $this->invoiceService->createInvoice($invoiceArray);
      }

      DB::commit();

      return [
        'success' => true,
        'sale' => $sale,
        'payment' => $payment,
        'invoice' => $invoice,
      ];
    } catch (Exception $e) {
      DB::rollBack();

      if (env('DB_CONNECTION') == 'postgres') {
          Sale::resetLastSequenceId($currentSaleSeqId);
          SaleItem::resetLastSequenceId($currentSaleItemSeqId);
          Payment::resetLastSequenceId($currentPaymentSeqId);
          Invoice::resetLastSequenceId($currentInvoiceSeqId);
      }
      \Log::error($e);

      return [
        'success' => false,
        'errors' => $e->getMessage()
      ];
    }
  }
}
