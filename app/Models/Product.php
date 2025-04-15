<?php

namespace App\Models;

use App\Traits\HasProductLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    public function activeStorage()
    {
        return $this->hasOne(ActiveStorage::class);
    }

    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }
}
