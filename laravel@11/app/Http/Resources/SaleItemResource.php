<?php
// Author: DarCasanova
// Date: 05/08/2025
// Time: 11:15pm

namespace App\Http\Resources;

use App\Helpers\NumberHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
          'item_id' => $this->item_id,
          'item_name' => $this->item_name,
          'category_name' => $this->category_name,
          'supplier_name' => $this->supplier_name,
          'total_sold' => $this->total_sold . ' ' . $this->formatUnit(),
          'total_cogs' => NumberHelper::formatCurrency($this->total_cogs),
          'total_revenue' => NumberHelper::formatCurrency($this->total_revenue),
          'total_gross_income' => NumberHelper::formatCurrency($this->total_gross_income),
      ];
    }

    private function formatUnit(): string
    {
        return $this->total_sold == 1 ? $this->unit_name : $this->unit_name . 's';
    }
}
