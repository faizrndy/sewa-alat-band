<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlatBandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'nama_alat' => $this->nama_alat,
        'kategori' => $this->kategori,
        'harga_sewa' => (int) $this->harga_sewa,
        'status' => $this->status,
        'deskripsi' => $this->deskripsi,
        'stok' => (int) $this->stok,
    ];
  }
}