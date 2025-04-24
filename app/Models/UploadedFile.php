<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UploadedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'upload_session_id',
        'original_name',
        'storage_path',
        'mime_type',
        'size',
        'extension',
        'checksum',
        'download_count',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(UploadSession::class);
    }

    public function getFileSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->size;
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    public function getStorageUrlAttribute(): string
    {
        return Storage::url($this->storage_path);
    }
}
