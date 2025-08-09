<?php

namespace App\Providers;

use App\Services\InventoryManager;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\POSManager;
use App\Services\SaleItemService;
use App\Services\SaleService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      $this->app->singleton(POSManager::class, function ($app) {
        return new POSManager(
          $app->make(SaleService::class),
          $app->make(SaleItemService::class),
          $app->make(PaymentService::class),
          $app->make(InventoryManager::class),
          $app->make(InvoiceService::class)
        );
      });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('whereNameIlike', function ($search) {
        return $this->where(function ($query) use ($search) {
            $query->whereRaw('LOWER(fname) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(mname) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(lname) LIKE ?', ['%' . strtolower($search) . '%']);
        });
        });

        Builder::macro('whereCompanyOrDefault', function ($companyId) {
          return $this->where(function ($query) use ($companyId) {
              $query->whereNull('company_id')
                  ->orWhere('company_id', $companyId);
          });
        });

        Builder::macro('whereLocation', function ($search) {
            return $this->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(city) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(province) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(country) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        });
    }
}
