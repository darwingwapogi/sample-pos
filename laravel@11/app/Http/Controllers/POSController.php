<?php
// Author: DarCasanova
// Date: 03/09/2025
// Time: 10:06pm
// Description: the main controller for POS

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Helpers\PriceCalculator;
use App\Helpers\VatCalculator;
use App\Services\POSManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class POSController extends Controller
{
    public function __construct(protected POSManager $posManager) {}

    public function processSale(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->posManager->processSale($request->all(), false);

        if (!$result['success']) {
            return response()->json($result, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($result);
    }

    public function recomputeFinalSellingPrice(Request $request): JsonResponse
    {
        $sellingPrice = $request['selling_price'] ?? 0.0;
        $percentageDiscount = $request['percentage_discount'] ?? 0.0;
        $discountAmount = $request['discount_amount'] ?? 0.0;
        $isVatEnabled = $request['is_vat_enabled'] ?? false;

        $result = [];
        $result['discounted_price'] = PriceCalculator::calculateDiscountedPrice($sellingPrice, $percentageDiscount, $discountAmount);

        $vatRelated = VatCalculator::computeFinalSellingPrice($sellingPrice, $result['discounted_price'],
            $isVatEnabled, Constants::VAT_PERCENTAGE);

        $result['vat_amount'] = $vatRelated['vat_amount'];
        $result['final_selling_price'] = $vatRelated['final_selling_price'];

        return response()->json($result);
    }
}
