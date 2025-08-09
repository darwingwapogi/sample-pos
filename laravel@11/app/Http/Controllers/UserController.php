<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\UserCollection\UserCollection;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function store(Request $request)
    {
        return UserService::create($request);
    }

    public function updateUser(Request $request, $id)
    {
        return $this->update($request, User::class, $id);
    }

    public function list() {
      /* TODO implement CurrentUser */
      $companyId = 1000;
      
      $collection = UserService::list($companyId);
      
      if (!$collection['success']) {
        return response()->json(['message' => 'Something went wrong.'], 500);
      }

      $filteredData = UserCollection::filter($collection['data']);

      return response()->json($filteredData, 200);
    }

    public function show(Request $request) {
      //implement method here
    }
}
