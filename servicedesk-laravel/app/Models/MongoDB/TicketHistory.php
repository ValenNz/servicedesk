<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;  //  Namespace yang benar

class TicketHistory extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ticket_histories';

    protected $fillable = [
        'ticket_id',
        'action',
        'old_value',
        'new_value',
        'performed_by',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public static function logChange($ticketId, $action, $oldValue, $newValue, $performedBy)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'performed_by' => $performedBy,
            'performed_at' => now(),
        ]);
    }
}