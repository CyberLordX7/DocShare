<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FileStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_files' => $this->files->count(),
            'total_size' => format_bytes($this->files->sum('size')),
            'total_size_in_bytes' => $this->files->sum('size'),
            'total_downloads' => $this->files->sum('download_count'),
            'expires_at' => $this->expires_at->format('Y-m-d H:i:s'),
            'expires_at_human' => $this->expires_at->diffForHumans(),
            'expires_at_iso' => $this->expires_at->toIso8601String(),
            'files' => FileResource::collection($this->files),

            'created_at_human' => $this->created_at->diffForHumans(),
            'updated_at_human' => $this->updated_at->diffForHumans(),
        ];
    }
}
