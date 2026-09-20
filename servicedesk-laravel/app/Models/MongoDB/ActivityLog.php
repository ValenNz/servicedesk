<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;  

class ActivityLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public static function log($userId, $action, $description, $metadata = [])
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}