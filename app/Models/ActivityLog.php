<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'description', 'ticket_id', 'user_id', 'seen'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper to quickly log an activity from anywhere in the app
    public static function log(string $type, string $description, ?int $ticketId = null, ?int $userId = null): void
    {
        static::create([
            'type' => $type,
            'description' => $description,
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'seen' => false,
        ]);
    }
}
