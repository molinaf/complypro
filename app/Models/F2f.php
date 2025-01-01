<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F2F extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $table = 'f2fs'; // Specify the table name

    public function authorisations()
    {
        return $this->belongsToMany(Authorisation::class);
    }
}
