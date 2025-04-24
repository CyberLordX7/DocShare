<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->original_name,
            'size' => format_bytes($this->size),
            'size_in_bytes' => $this->size,
            'downloads' => $this->download_count,
            'uploaded_at' => $this->created_at->format('Y-m-d H:i:s'),
            'uploaded_at_human' => $this->created_at->diffForHumans(),
            'uploaded_at_iso' => $this->created_at->toIso8601String(),
        ];
    }
}
