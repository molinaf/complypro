<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Induction extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function authorisations()
    {
        return $this->belongsToMany(Authorisation::class);
    }
}