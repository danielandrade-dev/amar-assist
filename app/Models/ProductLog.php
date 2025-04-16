<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductLog extends Model
{
    protected $fillable = [
        'product_id',
        'action',
        'old_values',
        'new_values',
        'user_id'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function scopeSearch(Builder $query, array $params): Builder
    {
        return $query->when(isset($params['search']), function ($query) use ($params) {
            $query->where('action', 'like', "%{$params['search']}%")
                ->orWhere('user_id', 'like', "%{$params['search']}%")
                ->orWhere('product_id', 'like', "%{$params['search']}%");
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
} 