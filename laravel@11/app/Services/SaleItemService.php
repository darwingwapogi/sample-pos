<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: used for creation of sale items
// Note: You can add as many functions if needed

namespace App\Services;

use App\Models\Item;
use App\Models\SaleItem;
use App\Common\Utility\ArrayUtil;
use App\Http\Resources\SaleItemCollection;
use Faker\Provider\Base;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SaleItemService extends BaseService {

    public static function createSaleItems(array $dataArray): void
    {
        foreach ($dataArray as $data) {
            self::create($data, new SaleItem());
        }
    }

    public static function updateSaleItems($data) {
        $id = ArrayUtil::valueAt($data, 'id');

        $modelData = [];

        /*TODO foreach (SaleItem::$fillableColumn as $column) {
          $modelData[$column] = ArrayUtil::valueAt($data, $column);
        } */

        return parent::update($modelData, $id, new SaleItem);
    }

    public static function list($data): SaleItemCollection|\Illuminate\Http\JsonResponse
    {
        try {
            $companyId = ArrayUtil::valueAt($data, 'company_id');
            $page = max((int) ($data['page'] ?? 1), 1);
            $perPage = max((int) ($data['count'] ?? 10), 10);
            $chunkSize = 500;
    
            $aggregated = [];
    
            Item::where('company_id', $companyId)
                ->with([
                    'category:id,name',
                    'supplier:id,name',
                    'unit:id,name',
                    'saleItem:id,item_id,quantity,cost_price,final_selling_price',
                ])
                ->chunk($chunkSize, function ($items) use (&$aggregated) {
                    foreach ($items as $item) {
                        if ($item->saleItem?->isEmpty()) {
                            continue;
                        }
    
                        $totals = [
                            'total_sold' => 0,
                            'total_cogs' => 0,
                            'total_revenue' => 0,
                            'total_gross_income' => 0,
                        ];
    
                        foreach ($item->saleItem as $saleItem) {
                            $totals['total_sold'] += $saleItem->quantity;
                            $totals['total_cogs'] += $saleItem->cost_price;
                            $totals['total_revenue'] += $saleItem->final_selling_price;
                            $totals['total_gross_income'] += ($saleItem->final_selling_price - $saleItem->cost_price);
                        }
    
                        $aggregated[] = [
                            'item_id' => $item->id,
                            'item_name' => $item->name,
                            'category_name' => $item->category->name ?? '',
                            'supplier_name' => $item->supplier->name ?? '',
                            'unit_name' => $item->unit->name ?? '',
                            ...$totals,
                        ];
                    }
                });
    
            $collection = collect($aggregated)->sortByDesc('total_cogs')->values();
            $total = $collection->count();
    
            $collection = $collection->map(fn ($item) => (object) $item);
    
            $paginated = new LengthAwarePaginator(
                $collection->forPage($page, $perPage)->values(),
                $total,
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
    
            return new SaleItemCollection($paginated);
        } catch (\Exception $e) {
            BaseService::logError($e);

            return response()->json([
                'error' => 'Something went wrong while fetching sold items.',
            ], 500);
        }
    }

    public static function showDetail($id): array
    {
        return parent::find($id, new SaleItem);
    }

    public static function remove($id): array
    {
        return parent::delete($id, new SaleItem);
    }
}
