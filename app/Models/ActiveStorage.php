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
    }
    public function Products()
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
}
