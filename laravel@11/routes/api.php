<?php
/*
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 4:30 PM
    Description: This is the api.php file that contains the routes for the API.
    Note: Common method for all controller is ['list', 'save', 'show', 'delete'].
 */

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DBConfigController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\SaleItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\EnumController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\SupplierController;
use App\ResourceCollection\DBConfigCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->get('/user', function (Request $request) {
    return response()->json(auth()->user());
});

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

$routes = [
    'super_admin' => [
        [
            'prefix' => 'user',
            'controller' => 'App\Http\Controllers\UserController',
        ],
        [
            'prefix' => 'company',
            'controller' => 'App\Http\Controllers\CompanyController',
        ]
    ],
    'admin' => [
        [
            'prefix' => 'supplier',
            'controller' => SupplierController::class,
        ],
        [
            'prefix' => 'category',
            'controller' => CategoryController::class,
        ],
        [
            'prefix' => 'item-type',
            'controller' => ItemTypeController::class,
        ],
        [
            'prefix' => 'item',
            'controller' => ItemController::class,
        ],
        [
            'prefix' => 'invoice',
            'controller' => InvoiceController::class,
        ],
        [
            'prefix' => 'unit',
            'controller' => UnitController::class,
        ],
        [
            'prefix' => 'customer',
            'controller' => CustomerController::class,
        ],
        [
            'prefix' => 'sales',
            'controller' => SaleController::class,
        ],
        [
            'prefix' => 'sale-item',
            'controller' => SaleItemController::class,
        ]
    ],
    'sales_representative' => [
        [
            'prefix' => 'invoice',
            'controller' => InvoiceController::class,
        ]
    ]
];

Route::middleware('jwt.auth')->group(function () use ($routes) {
    Route::get('menu/list', [MenuController::class, 'list']);

    foreach ($routes as $route) {
        foreach ($route as $item) {
            Route::prefix($item['prefix'])->controller($item['controller'])->group(function () {
                Route::get('list', 'list');
                Route::post('save', 'save');
                Route::get('show/{id}', 'show');
//                Route::delete('delete/{id}', 'remove'); TODO: uncomment this when client needs to delete data, but make sure to fix all validations to avoid deleting important data
            });
        }
    }

    Route::prefix('pos')->controller(POSController::class)->group(function () {
        Route::post('sale', 'processSale');
        Route::post('recompute-final-selling-price', 'recomputeFinalSellingPrice');
    });

    Route::prefix('enum')->controller(EnumController::class)->group(function () {
        Route::get('sale-status-list', 'saleStatuslist');
        Route::get('invoice-status-list', 'invoiceStatuslist');
        Route::get('payment-status-list', 'paymentStatuslist');
        Route::get('supplier-status-list', 'supplierStatuslist');
        Route::get('transaction-type-list', 'transactionTypelist');
    });

    Route::prefix('db-config')->controller(DBConfigController::class)->group(function () {
        Route::get('category-list', 'categoryList');
        Route::get('item-type-list', 'itemTypeList');
        Route::get('unit-list', 'unitList');
        Route::get('supplier-list', 'supplierList');
        Route::get('payment-method-list', 'paymentMethodList');
        Route::get('payment-status-list', 'paymentStatusList');
        Route::get('transaction-type-list', 'transactionTypeList');
        Route::get('invoice-status-list', 'invoiceStatusList');
        Route::get('item-status-list', 'itemStatusList');
    });

    Route::prefix('item')->controller(ItemController::class)->group(function () {
        Route::post('compute-final-selling-price', 'computeFinalSellingPrice');
        Route::post('update-stock', 'updateStock');
    });

});
