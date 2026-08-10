<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'change_data',
        'created_at',
    ];

    protected $casts = [
        'change_data' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getModelNameAttribute(): string
    {
        return class_basename($this->auditable_type);
    }

    public function getFormattedChangesAttribute(): array
    {
        $hiddenGlobal = config('audit.hidden_fields.default', []);
        $hiddenForModel = config("audit.hidden_fields.{$this->auditable_type}", []);
        $hiddenFields = array_merge($hiddenGlobal, $hiddenForModel);

        $labels = config("audit.field_labels.{$this->auditable_type}", []);

        $formatted = [];

        foreach ($this->change_data ?? [] as $field => $diff) {
            if (in_array($field, $hiddenFields)) {
                continue;
            }

            $label = $labels[$field] ?? ucwords(str_replace('_', ' ', $field));

            $formatted[$label] = [
                'old' => $this->resolveValue($field, $diff['old']),
                'new' => $this->resolveValue($field, $diff['new']),
            ];
        }

        return $formatted;
    }

    public function getDisplaySummaryAttribute(): ?string
    {
        if (!in_array($this->action, ['created', 'deleted'])) {
            return null;
        }

        $field = config("audit.display_field.{$this->auditable_type}", config('audit.display_field.default'));

        $diff = $this->change_data[$field] ?? null;

        if (!$diff) {
            return null;
        }

        $rawValue = $this->action === 'created' ? $diff['new'] : $diff['old'];

        return $this->resolveValue($field, $rawValue);
    }

    private function resolveValue(string $field, $value)
    {
        if ($value === null) {
            return null;
        }

        $resolvable = config("audit.resolvable_fields.{$this->auditable_type}", []);

        if (!isset($resolvable[$field])) {
            return $value;
        }

        [$relatedModel, $displayAttribute] = $resolvable[$field];

        $related = $relatedModel::find($value);

        return $related ? $related->{$displayAttribute} : "#{$value} (deleted)";
    }
}