<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => ['required', 'string'],
            'product_id' => ['required', 'string'],
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'integer'],
            'unit_cost' => ['nullable', 'integer', 'min:0'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_id' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
