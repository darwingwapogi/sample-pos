<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ItemService;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;

class ItemServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_create_item()
    {
        $data = [
            'name' => 'Test Item',
            'brand' => $this->faker->company,
            'model' => $this->faker->word,
            'description' => $this->faker->sentence,
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'reorder_level' => $this->faker->numberBetween(1, 10),
            'cost_price' => $this->faker->randomFloat(2, 1, 100),
            'selling_price' => $this->faker->randomFloat(2, 1, 200),
            'category_id' => \App\Models\Category::factory()->create()->id,
            'unit_id' => \App\Models\Unit::factory()->create()->id,
            'company_id' => \App\Models\Company::factory()->create()->id,
            'item_type_id' => \App\Models\ItemType::factory()->create()->id,
            'supplier_id' => \App\Models\Supplier::factory()->create()->id,
            'status_id' => \App\Models\ItemStatus::factory()->create()->id,
        ];

        $result = ItemService::createOrUpdate($data);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('items', ['name' => 'Test Item']);
    }

    public function test_update_item()
    {
        $item = Item::factory()->create();
        
        $modelData = [];

        foreach (Item::$fillableColumn as $column) {
            $modelData[$column] = $item->$column;
        }

        if (isset($item['id'])) {
            $modelData['id'] = $item->id;
        }

        $modelData['name'] = 'Updated Item';
        $modelData['brand'] = 'Updated Brand';
        $modelData['model'] = 'Updated Model';
        $modelData['description'] = 'Updated Description';
        $modelData['stock_quantity'] = 20;
        $modelData['reorder_level'] = 10;
        $modelData['cost_price'] = 200.00;
        $modelData['selling_price'] = 250.00;


        $result = ItemService::createOrUpdate($modelData);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'name' => 'Updated Item',
            'brand' => 'Updated Brand',
            'model' => 'Updated Model',
            'description' => 'Updated Description',
            'stock_quantity' => 20,
            'reorder_level' => 10,
            'cost_price' => 200.00,
            'selling_price' => 250.00,
        ]);
    }

    // public function test_list_items()
    // {
    //     Item::factory()->count(5)->create(['company_id' => 1]);

    //     $result = ItemService::list(['company_id' => 1]);

    //     $this->assertTrue($result['success']);
    //     $this->assertCount(5, $result['data']);
    // }

    public function test_remove_item()
    {
      
        $item = Item::factory()->create();

        $result = ItemService::remove($item->id);

        $this->assertTrue($result['success']);
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    
    public function test_show_detail_item()
    {
        $data = [
            'name' => 'Test Item',
            'brand' => $this->faker->company,
            'model' => $this->faker->word,
            'description' => $this->faker->sentence,
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'reorder_level' => $this->faker->numberBetween(1, 10),
            'cost_price' => $this->faker->randomFloat(2, 1, 100),
            'selling_price' => $this->faker->randomFloat(2, 1, 200),
            'category_id' => \App\Models\Category::factory()->create()->id,
            'unit_id' => \App\Models\Unit::factory()->create()->id,
            'company_id' => \App\Models\Company::factory()->create()->id,
            'item_type_id' => \App\Models\ItemType::factory()->create()->id,
            'supplier_id' => \App\Models\Supplier::factory()->create()->id,
            'status_id' => \App\Models\ItemStatus::factory()->create()->id,
        ];

        $item = Item::factory()->create($data);

        $result = ItemService::showDetail($item->id);

        $this->assertNotNull($result['success']);
        $this->assertDatabaseHas('items', ['name' => 'Test Item']);
    }

}
