<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'user_id',
        'title',
        'description',
        'status',
        'assigned_team',
        'assigned_by',
        'assigned_at',
        'attachment_path',
        'attachment_name',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }

    // Auto-generate a unique reference number when a ticket is created
    protected static function booted()
    {
        static::creating(function (Ticket $ticket) {
            if (empty($ticket->reference_number)) {
                $ticket->reference_number = 'HD-' . now()->format('Y') . '-' . strtoupper(Str::random(6));
            }
            $ticket->status = $ticket->status ?: 'open';
        });
    }

    // ---- Relationships ----

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class)->latest();
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class)->latest();
    }

    // ---- Status helpers ----

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function hasAttachment(): bool
    {
        return ! empty($this->attachment_path) || $this->attachments()->exists();
    }

    // Ticket can only be edited/deleted by the user until PM has reviewed (assigned) it
    public function isEditableByUser(): bool
    {
        return $this->isOpen();
    }

    // Assign this ticket to a team (called by Project Manager)
    public function assignToTeam(string $team, User $projectManager): void
    {
        $this->update([
            'assigned_team' => $team,
            'assigned_by' => $projectManager->id,
            'assigned_at' => now(),
            'status' => 'in_progress',
        ]);
    }
}