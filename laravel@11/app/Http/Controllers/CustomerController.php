<?php

namespace App\Http\Controllers;

use App\Common\CurrentUser;
use App\ResourceCollection\CustomerCollection;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends BaseController
{
    // Display a listing of the resource.
    public function list(Request $request)
    {
        $currentUser = new CurrentUser();
        $companyId = $currentUser->getCompanyId();

        $data = $request->all();
        $data['company_id'] = $companyId;

        $collection = CustomerService::list($data);

        if (!$collection['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        $filteredData = CustomerCollection::filterData($collection['data']);

        return response()->json($filteredData);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
        ]);

        $result = CustomerService::createCustomer($validatedData, true);

        if (!$result['success']) {
            return response()->json(['errors' => 'Creation failed.'], 500);
        }

        return response()->json($result['created_data'], 201);
    }

    // Display the specified resource.
    public function show($id)
    {
        $result = CustomerService::showDetail($id);

        if (!$result['success']) {
            return response()->json(['errors' => 'Something went wrong.'], 500);
        }

        return response()->json($result['data']);
    }

    // Update the specified resource in storage.
    // public function update(Request $request, $id)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|string|email|max:255|unique:customers,email,' . $id,
    //     ]);

    //     $validatedData['id'] = $id;

    //     $result = CustomerService::updateCustomer($validatedData);

    //     if (!$result['success']) {
    //         return response()->json(['errors' => 'Update failed.'], 500);
    //     }

    //     return response()->json($result['updated_data']);
    // }

    // Remove the specified resource from storage.
    public function remove($id)
    {
        $result = CustomerService::remove($id);

        if (!$result['success']) {
            return response()->json(['errors' => 'Deletion failed.'], 500);
        }

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
