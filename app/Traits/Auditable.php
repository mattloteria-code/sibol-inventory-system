<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $changes = [];

            foreach ($model->attributesToArray() as $field => $value) {
                if (in_array($field, ['created_at', 'updated_at'])) {
                    continue;
                }

                $changes[$field] = ['old' => null, 'new' => $value];
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'change_data' => $changes,
                'created_at' => now(),
            ]);
        });

        static::updated(function ($model) {
            $changes = [];

            foreach ($model->getChanges() as $field => $newValue) {
                if ($field === 'updated_at') {
                    continue;
                }

                $changes[$field] = [
                    'old' => $model->getOriginal($field),
                    'new' => $newValue,
                ];
            }

            if (empty($changes)) {
                return;
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'change_data' => $changes,
                'created_at' => now(),
            ]);
        });

        static::deleted(function ($model) {
            $changes = [];

            foreach ($model->attributesToArray() as $field => $value) {
                if (in_array($field, ['created_at', 'updated_at'])) {
                    continue;
                }

                $changes[$field] = ['old' => $value, 'new' => null];
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'change_data' => $changes,
                'created_at' => now(),
            ]);
        });
    }
}