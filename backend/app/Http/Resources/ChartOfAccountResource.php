<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountResource extends JsonResource
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
            'kode' => $this->kode,
            'nama' => $this->nama,
            'id_kategori' => $this->id_kategori,
            // Mengambil relasi kategori jika sudah dimuat
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
