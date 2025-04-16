<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActiveStorage extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['filename', 'uuid', 'service_name', 'content_type', 'product_id'];
    protected $table = 'active_storages';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->service_name = config('filesystems.default');
            $model->content_type = $model->getContentType();
            $model->product_id = $model->product_id ?? null;
        });

        static::deleting(function ($model) {
            if ($model->isForceDeleting()) {
                Storage::disk($model->service_name)->delete($model->filename);
            }
        });
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getContentType()
    {
        $extension = pathinfo($this->filename, PATHINFO_EXTENSION);
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    public static function uploadImage($file, $product = null)
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        $storage = new self();
        $storage->filename = $filename;
        $storage->product_id = $product?->id;
        
        Storage::disk(config('filesystems.default'))->put($filename, file_get_contents($file));
        
        $storage->save();
        
        return $storage;
    }

    public function updateImage($file)
    {
        Storage::disk($this->service_name)->delete($this->filename);
        
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        Storage::disk($this->service_name)->put($filename, file_get_contents($file));
        
        $this->filename = $filename;
        $this->content_type = $this->getContentType();
        $this->save();
        
        return $this;
    }

    public function getUrl()
    {
        return Storage::disk($this->service_name)->url($this->filename);
    }
}
