<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'title',
        'message',
        'link',
        'related_type',
        'related_id',
        'is_read',
        'created_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    public static function existsUnreadFor(string $type, ?string $relatedType, ?int $relatedId): bool
    {
        return static::where('type', $type)
            ->where('related_type', $relatedType)
            ->where('related_id', $relatedId)
            ->where('is_read', false)
            ->exists();
    }
}