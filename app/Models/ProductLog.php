<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Product::class);
    }
} 