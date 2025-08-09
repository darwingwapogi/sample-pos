<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $this->call([
            CategorySeeder::class,
            UserTypeSeeder::class,
            ItemStatusSeeder::class,
            ItemTypeSeeder::class,
            PaymentStatusSeeder::class,
            PaymentMethodSeeder::class,
            UnitSeeder::class,
            InvoiceStatusSeeder::class,
            TransactionTypeSeeder::class,
      ]);

      if (strpos(env('APP_ENV'), 'prod') === false) {
        $this->call([
            CompanySeeder::class,
            CustomerSeeder::class,
            ItemSeeder::class,
            UserSeeder::class,
            SupplierSeeder::class,
            SaleSeeder::class,
            SaleItemSeeder::class,
            PaymentSeeder::class,
            InvoiceSeeder::class,
            InvoicePaymentSeeder::class
        ]);
      }
    }
}
