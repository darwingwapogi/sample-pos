<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function list() {
        //TODO: Implement this method
        // This method should return the list of menu items for the sidebar
        $result = [
            'menu' => $this->setAdminMenu()
        ];

        return response()->json($result);
    }

    public function setAdminMenu() {
        return [
                [
                    'label' => 'Dashboard',
                    'icon' => 'bi-speedometer',
                    'link' => '/dashboard'
                ],
                [
                    'label' => 'Sales Page',
                    'icon' => 'bi-cash-coin',
                    'link' => '/sales'
                ],
                [
                    'label' => 'Point of Sale',
                    'icon' => 'bi-postcard',
                    'link' => '/point-of-sale'
                ],
                [
                    'label' => 'Items',
                    'icon' => 'bi-box-seam-fill',
                    'link' => '/items/list'
                ],
                [
                    'label' => 'Item Types',
                    'icon' => 'bi-card-checklist',
                    'link' => '/item-types/list'
                ],
                [
                    'label' => 'Categories',
                    'icon' => 'bi-tags',
                    'link' => '/categories/list',
                ],
                // [
                //     'label' => 'Purchases',
                //     'icon' => 'bi-file-earmark-text',
                //     'link' => '/purchases'
                // ],
                // [
                //     'label' => 'Expenses',
                //     'icon' => 'bi-wallet2',
                //     'link' => '/expenses'
                // ],
                // [
                //     'label' => 'Reports',
                //     'icon' => 'bi-file-earmark-bar-graph',
                //     'link' => '/reports'
                // ],
                [
                    'label' => 'Units',
                    'icon' => 'bi bi-rulers',
                    'link' => '/units/list'
                ],
                // [
                //     'label' => 'Customer',
                //     'icon' => 'bi bi-people',
                //     'link' => '/customers/list'
                // ],
                [
                    'label' => 'Suppliers',
                    'icon' => 'bi bi-person-badge',
                    'link' => '/suppliers/list'
                ],
            ];
    }
}
