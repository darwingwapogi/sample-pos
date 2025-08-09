<?php

namespace App\Http\Controllers;

use App\Common\CurrentUser;
use App\Services\UnitService;
use Illuminate\Http\Request;

class UnitController extends BaseController
{
    public function list(Request $request)
  {
    $currentUser = new CurrentUser();
    $companyId = $currentUser->getCompanyId();

    $data = $request->all();
    $data['company_id'] = $companyId;

    $collection = UnitService::list($data);

    if (!$collection['success']) {
      return response()->json(['errors' => 'Something went wrong.'], 500);
    }

    return response()->json($collection['data']);
  }

  public function show($id)
  {
    $collection = UnitService::showDetail($id);

    if (!$collection['success']) {
      return response()->json(['errors' => 'Something went wrong.'], 500);
    }
    return response()->json($collection['data']);
  }

  public function save(Request $request)
  {
    $currentUser = new CurrentUser();
    $companyId = $currentUser->getCompanyId();

    $data = $request->all();
    $data['company_id'] = $companyId;

    $created = UnitService::createOrUpdate($data);

    if (!$created['success']) {
        if (isset($created['isDbExists'])) {
            return response()->json(['error' => $created['error']], 409);
        }
        return response()->json(['errors' => 'Something went wrong.'], 500);
    }

    return response()->json($created, 201);
  }

  public function remove($id): \Illuminate\Http\JsonResponse
  {
    $deleted = UnitService::remove($id);

    if (!$deleted['success']) {
      return response()->json(['errors' => 'Something went wrong.'], 500);
    }

    return response()->json($deleted, 200);
  }
}
