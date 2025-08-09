<?php

namespace App\Http\Controllers;

use App\Common\CurrentUser;
use App\ResourceCollection\SaleCollection;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends BaseController
{
    public function list(Request $request)
    {
        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $data = $request->all();
        $data['company_id'] = $companyId;

        $collection = SaleService::list($data);

        if (!$collection['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        $filteredData = SaleCollection::filterData($collection['data']);

        return response()->json($filteredData);
    }

    public function show($id)
    {
        $collection = SaleService::showDetail($id);

        if (!$collection['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        $filteredData = SaleCollection::transform($collection['data']);

        return response()->json($filteredData);
    }
}
