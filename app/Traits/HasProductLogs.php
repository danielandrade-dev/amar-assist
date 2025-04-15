<?php

namespace App\Traits;

use App\Models\ProductLog;
use Illuminate\Support\Facades\Auth;

trait HasProductLogs
{
    protected static function bootHasProductLogs()
    {
        static::created(function ($model) {
            $model->logActivity('created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $model->logActivity('updated', $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getAttributes(), null);
        });
    }

    public function logs()
    {
        return $this->hasMany(ProductLog::class);
    }

    protected function logActivity(string $action, ?array $oldValues = null, ?array $newValues = null)
    {
        return $this->logs()->create([
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'user_id' => Auth::id()
        ]);
    }
} 