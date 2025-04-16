<?php

namespace App\Models;

use App\Traits\HasProductLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasProductLogs;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'cost',
        'status',
        'slug',
    ];

    public function activeStorage()
    {
        return $this->hasOne(ActiveStorage::class);
    }

    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }
    public function scopeSearch(Builder $query, array $params): Builder
    {
        $query->when(isset($params['search']), function ($query) use ($params) {
            $query->where('name', 'like', "%{$params['search']}%")
                ->orWhere('description', 'like', "%{$params['search']}%");
        })->when(isset($params['startDate']), function ($query) use ($params) {
            $query->where('created_at', '>=', $params['startDate']);
        })->when(isset($params['endDate']), function ($query) use ($params) {
            $query->where('created_at', '<=', $params['endDate']);
        });
        return $query;
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::random(10);
        });
    }

}
