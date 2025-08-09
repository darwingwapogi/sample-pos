<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierServiceTest extends TestCase
{
  use RefreshDatabase;

  public function test_create_supplier()
  {
      $data = [
          'company_id' => 1,
          'name' => 'Test Supplier',
          'contact_person' => 'John Doe',
          'email' => 'test@example.com',
          'phone' => '1234567890',
          'website' => 'https://example.com',
          'address' => '123 Test Street',
      ];

      $result = SupplierService::createOrUpdate($data);

      $this->assertTrue($result['success']);
      $this->assertDatabaseHas('suppliers', ['name' => 'Test Supplier']);
  }

  public function test_update_supplier()
  {
      $supplier = Supplier::factory()->create();

      $data = [
          'id' => $supplier->id,
          'company_id' => $supplier->company_id,
          'name' => 'Updated Supplier',
          'contact_person' => 'Jane Doe',
          'email' => 'updated@example.com',
          'phone' => '0987654321',
          'website' => 'https://updated.com',
          'address' => '456 Updated Street',
      ];

      $result = SupplierService::createOrUpdate($data);

      $this->assertTrue($result['success']);
      $this->assertDatabaseHas('suppliers', ['name' => 'Updated Supplier']);
  }

  public function test_list_suppliers()
  {
      Supplier::factory()->count(5)->create(['company_id' => 1]);

      $result = SupplierService::list(['company_id' => 1]);

      $this->assertTrue($result['success']);
      $this->assertCount(5, $result['data']);
  }

  public function test_find_supplier()
  {
      $supplier = Supplier::factory()->create();

      $result = SupplierService::find($supplier->id);

      $this->assertTrue($result['success']);
      $this->assertEquals($supplier->id, $result['data']->id);
  }

  public function test_destroy_supplier()
  {
      $supplier = Supplier::factory()->create();

      $result = SupplierService::remove($supplier->id);

      $this->assertTrue($result['success']);
      $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
  }
}
