<?php

namespace App\Http\Controllers;

use App\Common\CurrentUser;
use App\ResourceCollection\CategoryCollection;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
  public function list(Request $request)
  {
    $currentUser = new CurrentUser();
    $companyId = $currentUser->getCompanyId();

    $data = $request->all();
    $data['company_id'] = $companyId;

    $collection = CategoryService::list($data);

    if (!$collection['success']) {
      return response()->json(['errors' => 'Something went wrong.'], 500);
    }

    $filteredData = CategoryCollection::filterData($collection['data']);

    return response()->json($filteredData);
  }

  public function show($id)
  {
    $collection = CategoryService::showDetail($id);

    if (!$collection['success']) {
      return response()->json(['errors' => 'Something went wrong.'], 500);
    }

    $filteredData = CategoryCollection::transform($collection['data']);

    return response()->json($filteredData);
  }

    /**
     * @throws \Exception
     */
    public function save(Request $request)
  {
    $currentUser = new CurrentUser();
    $companyId = $currentUser->getCompanyId();

    $data = $request->all();
    $data['company_id'] = $companyId;

    $created = CategoryService::createOrUpdate($data);

    if (!$created['success'] ) {
        if (isset($created['isDbExists'])) {
            return response()->json(['error' => $created['error']], 409);
        }

        return response()->json(['errors' => 'Something went wrong.'], 500);
    }

    return response()->json($created, 201);
  }

  public function remove($id)
  {
    $deleted = CategoryService::remove($id);
    if (!$deleted['success']) {
      return response()->json(['errors' => 'Something went wrong.'], 500);
    }

    return response()->json($deleted, 200);
  }
}
