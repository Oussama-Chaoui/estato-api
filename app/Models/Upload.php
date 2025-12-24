<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Upload extends BaseModel
{
    public static $cacheKey = 'uploads';

    protected $fillable = [
        'name',
        'path',
    ];

    protected $appends = [
        'url',
    ];

    protected static function booted()
    {
        parent::booted();

        static::deleted(
            function ($item) {
                $item->deleteFile();
            }
        );
    }

    public function getPathWithoutDiskAttribute()
    {
        // Remove the /storage/ prefix to get the relative path
        return Str::of($this->path)->remove('/storage/')->toString();
    }

    public function getUrlAttribute()
    {
        return url($this->path);
    }

    public function deleteFile(): bool
    {
        try {
            if (Storage::disk('cloud')->delete($this->path_without_disk)) {
                return true;
            }
            throw new \Exception("Couldn't delete file $this->path - upload id : $this->id");
        } catch (\Throwable $th) {
            Log::error('Upload : '.$th->getMessage());
        }

        return false;
    }

    public function rules($id = null)
    {
        $id = $id ?? request()->route('id');

        return [
            'name' => 'nullable|string',
            'path' => 'required|string',
        ];
    }
}
