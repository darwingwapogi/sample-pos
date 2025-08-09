<?php

namespace App\Http\Controllers;

use App\Common\CurrentUser;
use App\ResourceCollection\ItemTypeCollection;
use Illuminate\Http\Request;
use App\Services\ItemTypeService;

class ItemTypeController extends BaseController
{
    public function list(Request $request)
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $data = $request->all();
      $data['company_id'] = $companyId;

      $collection = ItemTypeService::list($data);

      if (!$collection['success']) {
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      $filteredData = ItemTypeCollection::filterData($collection['data']);

      return response()->json($filteredData);
    }

    public function show($id)
    {
      $collection = ItemTypeService::showDetail($id);

      if (!$collection['success']) {
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      $filteredData = ItemTypeCollection::transform($collection['data']);

      return response()->json($filteredData);
    }

    public function save(Request $request)
    {
      $currentUser = new CurrentUser();
      $companyId = $currentUser->getCompanyId();

      $data = $request->all();
      $data['company_id'] = $companyId;

      $created = ItemTypeService::createOrUpdate($data);
      
      if (!$created['success'] ) {
        if (isset($created['isDbExists'])) {
            return response()->json(['error' => $created['error']], 409);
        }
        
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      return response()->json($created, 201);
    }

    public function remove(Request $request)
    {
      $id = $request->input('id');

      $deleted = ItemTypeService::remove($id);
      if (!$deleted['success']) {
        return response()->json(['errors' => 'Something went wrong.'], 500);
      }

      return response()->json($deleted, 204);
    }
}
