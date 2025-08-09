<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplierService;
use App\Common\CurrentUser;
use App\ResourceCollection\SupplierCollection;

class SupplierController extends BaseController
{
    public function configList()
    {
        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $data = [
            'company_id' => $companyId
        ];

        $collection = SupplierService::list($data);

        if (!$collection['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        $filteredData = SupplierCollection::filterData($collection['data']);

        return response()->json($filteredData);
    }

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $data = $request->all();
        $data['company_id'] = $companyId;

        $collection = SupplierService::list($data);

        if (!$collection['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        $filteredData = SupplierCollection::filterData($collection['data']);

        return response()->json($filteredData);
    }

    public function show($id)
    {
        $collection = SupplierService::detail($id);

        if (!$collection['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        $filteredData = SupplierCollection::transform($collection['data']);

        return response()->json($filteredData);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $data = $request->all();
        $data['company_id'] = $companyId;

        $created = SupplierService::createOrUpdate($data);

        if (!$created['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        return response()->json($created, 201);
    }

    public function remove($id)
    {
        $deleted = SupplierService::remove($id);
        if (!$deleted['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        return response()->json($deleted, 200);
    }
}
