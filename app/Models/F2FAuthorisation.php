<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F2FAuthorisation extends Model
{
    use HasFactory;

    protected $table = 'f2f_authorisation'; // Explicitly define the table name

    protected $fillable = ['f2f_id', 'authorisation_id'];

    public function authorisation()
    {
        return $this->belongsTo(Authorisation::class, 'authorisation_id');
    }

    public function f2f()
    {
        return $this->belongsTo(F2F::class, 'f2f_id');
    }
}
