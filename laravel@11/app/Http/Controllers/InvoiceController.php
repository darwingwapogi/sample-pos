<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends BaseController
{
    public function store(Request $request)
    {
        $invoiceData = $request->toArray();

        $result = ['success' => false];

        // Validation rules
        $rules = [
            '*.item_type' => 'required|string',
            '*.item_price' => 'required|numeric',
            '*.quantity' => 'required|integer',
            '*.total' => 'required|numeric',
            '*.discount' => 'nullable|numeric',
        ];

        $validator = validator()->make($invoiceData, $rules);

        if ($validator->fails()) {
            $result['errors'] = $validator->errors();
            return response()->json($result, 422);
        }

        try {
            DB::transaction(function () use ($invoiceData) {
                foreach ($invoiceData as $item) {
                    $arrayData = [
                        "item_name" => "Ice",
                        "item_type" => $item['item_type'],
                        "item_price" => $item['item_price'],
                        "quantity" => $item['quantity'],
                        "total" => $item['total'],
                        "discount" => $item['discount'],
                        "is_paid" => true,
                        "is_credit" => false,
                    ];

                    $invoice = Invoice::create($arrayData);
                }
            });

            $result['success'] = true;
            $result['invoices'] = $invoiceData;
        } catch (\Throwable $th) {
            Log::error('Failed to create invoices: ' . $th->getMessage());
            $result['error'] = $th->getMessage();
        }

        return response()->json($result);
    }
}
