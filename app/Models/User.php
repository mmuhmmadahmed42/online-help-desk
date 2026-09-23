<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ---- Role helpers ----

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isProjectManager(): bool
    {
        return $this->role === 'project_manager';
    }

    public function isBackendTeam(): bool
    {
        return $this->role === 'backend_team';
    }

    public function isFrontendTeam(): bool
    {
        return $this->role === 'frontend_team';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ---- Relationships ----

    // Tickets this user created (role: user)
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Tickets this user assigned as a Project Manager
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_by');
    }
}