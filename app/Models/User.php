<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_USER = 'U';
    const ROLE_SUPERIOR = 'S';
    const ROLE_MANAGER = 'M';
    const ROLE_ADMIN = 'A';

    // Status constants
    const STATUS_ACTIVE = 'A';
    const STATUS_PENDING = 'P';
    const STATUS_INACTIVE = 'I';
    const STATUS_SUSPENDED = 'S';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'company_id',
    ];

    /**
     * A User belongs to a Company (U and S only).
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A Manager (M) can belong to many companies.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }
    public function isAuthenticatable()
    {
        return $this->status !== 'P';
    }
    public function authorisations()
    {
        return $this->hasMany(UserAuthorisation::class);
    }
    
}
