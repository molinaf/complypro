<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'email',
    ];

    /**
     * A Company has many Users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * A Company has many Managers (M).
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'company_user')->wherePivot('role', 'M');
    }
}
