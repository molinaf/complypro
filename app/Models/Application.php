<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['endorser_id', 'user_id', 'endorsement_date', 'approver_id', 'approved_date', 'status'];

    protected $casts = [
        'endorsement_date' => 'datetime',
        'approved_date' => 'datetime',
    ];

    public function endorser()
    {
        return $this->belongsTo(User::class, 'endorser_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function userAuthorisations()
    {
        return $this->hasMany(UserAuthorisation::class, 'application_id');
    }
}
