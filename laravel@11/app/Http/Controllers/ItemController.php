<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Common\CurrentUser;
use App\Helpers\PriceCalculator;
use App\Helpers\VatCalculator;
use App\ResourceCollection\ItemCollection;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends BaseController
{
    public function list(Request $request): JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $data = $request->all();
      $data['company_id'] = $companyId;

      $collection = ItemService::list($data);

      if (!$collection['success']) {
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      $filteredData = ItemCollection::filterData($collection['data']);

      return response()->json($filteredData);
    }

    public function show($id): JsonResponse
    {
      $collection = ItemService::showDetail($id);

      if (!$collection['success']) {
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      $filteredData = ItemCollection::transform($collection['data']);

      return response()->json($filteredData);
    }

    /**
     * @throws \Exception
     */
    public function save(Request $request): JsonResponse
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $data = $request->all();
      $data['company_id'] = $companyId;

      $created = ItemService::createOrUpdate($data);

      if (!$created['success']) {
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      return response()->json($created, 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function remove($id): JsonResponse
    {
      $deleted = ItemService::remove($id);
      if (!$deleted['success']) {
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      return response()->json($deleted, 204);
    }

    public function computeFinalSellingPrice(Request $request): JsonResponse
    {
        $costPrice = $request['cost_price'];
        $markup = $request['markup'] ?? 0.0;
        $markupAmount = $request['markup_amount'] ?? 0.0;
        $percentageDiscount = $request['percentage_discount'] ?? 0.0;
        $discountAmount = $request['discount_amount'] ?? 0.0;
        $isVatEnabled = $request['is_vat_enabled'] ?? false;

        $result = [];
        $result['selling_price'] = PriceCalculator::calculateSellingPrice($costPrice, $markup, $markupAmount);
        $result['discounted_price'] = PriceCalculator::calculateDiscountedPrice($result['selling_price'], $percentageDiscount, $discountAmount);

        $vatRelated = VatCalculator::computeFinalSellingPrice($result['selling_price'], $result['discounted_price'],
            $isVatEnabled, Constants::VAT_PERCENTAGE);

        $result['vat_amount'] = $vatRelated['vat_amount'];
        $result['final_selling_price'] = $vatRelated['final_selling_price'];

        return response()->json($result);
    }

    /**
     * @throws \Exception
     */
    public function updateStock(Request $request): JsonResponse
    {
        $data = $request->only('id','quantity','movement_type','remarks');

        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $data['company_id'] = $companyId;

        $updated = ItemService::updateStock($data);

        if (!$updated['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        return response()->json($updated, 201);
    }
}
