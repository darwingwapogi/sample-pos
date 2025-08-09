<?php

use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index']);

$uri = [
    '/login',
    '/home',
    '/dashboard',
    '/panels',
    '/sales',
    '/users',

    //POS
    '/point-of-sale',
    
    //Categories
    '/categories/list',

    //Units
    '/units/list',

    //Customers
    '/customers/list',

    //Items
    '/items/list',

    //Item Types
    '/item-types/list',

    //Suppliers
    '/suppliers/list',

    //Not Found
    '/notFound',
];

Route::group(['controller' => IndexController::class], function () use ($uri) {
    foreach ($uri as $u) {
        Route::get($u, 'index');
    }
});