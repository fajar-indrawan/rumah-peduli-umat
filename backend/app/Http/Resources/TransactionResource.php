<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        
        // Filter object
        return [
            'id' => $this->id,
            'tanggal' => $this->tanggal,
            'kode_coa' => $this->kode_coa,
            // // Mengambil relasi chart_of_account jika sudah dimuat
            'chart_of_account' => new ChartOfAccountResource($this->whenLoaded('chartOfAccount')),
            'desc' => $this->desc,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
