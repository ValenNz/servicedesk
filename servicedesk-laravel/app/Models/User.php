<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke tickets yang dibuat
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    // Relasi ke tickets yang di-assign
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    // Relasi ke employee
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    // Check jika admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check jika employee
    public function isEmployee()
    {
        return $this->role === 'employee';
    }
}