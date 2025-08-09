<?php

namespace App\Http\Controllers;

use App\Common\CurrentUser;
use App\Http\Resources\SaleItemCollection;
use App\ResourceCollection\SaleCollection;
use App\Services\SaleItemService;
use Illuminate\Http\Request;

class SaleItemController extends BaseController
{
    public function list(Request $request): SaleItemCollection|\Illuminate\Http\JsonResponse
    {
        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $data = $request->all();
        $data['company_id'] = $companyId;

        return SaleItemService::list($data);
    }

    public function show($id)
    {
        $collection = SaleItemService::showDetail($id);

        if (!$collection['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        $filteredData = SaleCollection::transform($collection['data']);

        return response()->json($filteredData);
    }
}
