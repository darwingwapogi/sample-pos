<?php
// Author: DarCasanova
// Date: 05/08/2025
// Time: 11:15pm

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleItemCollection extends ResourceCollection
{
    public $collects = SaleItemResource::class;
}
