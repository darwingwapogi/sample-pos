<?php
// Author: aamor
// Date: 03/13/2025

namespace Tests\Unit;

use App\Models\BaseModel;
use App\Models\Customer;
use App\Models\InvoiceStatus;
use App\Models\Sale;
use App\Models\User;
use App\Services\SaleService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Double;

class SaleServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_creates_a_sale(){

        $customerIds = Customer::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        Log::debug('$customerIds: ' . json_encode($customerIds , JSON_PRETTY_PRINT));
        Log::debug('$userIds: ' . json_encode($userIds , JSON_PRETTY_PRINT));


        // Arrange: Prepare the data
        $data = [
            'customer_id' => $customerIds[array_rand($customerIds)],
            'user_id' => $userIds[array_rand($userIds)],
            'company_id' => BaseModel::$companyId,
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => 'pending',
        ];

        // Act: Call the service to create a sale
        $createdSale = SaleService::createSale($data, true);
        Log::debug('$createdSale: ' . json_encode($createdSale , JSON_PRETTY_PRINT));
        $sale = $createdSale['created_data'];

        // Assert: Check if the sale was successfully created
        $this->assertTrue($createdSale['success']);
        $this->assertDatabaseHas('sales', [
            'customer_id' => $sale['customer_id'],
            'user_id' => $sale['user_id'],
            'company_id' => $sale['company_id'],
            'total_amount' => $sale['total_amount'],
            'status' => $sale['status'],
        ]);
    }
}
