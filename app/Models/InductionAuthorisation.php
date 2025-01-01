<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InductionAuthorisation extends Model
{
    use HasFactory;

    protected $table = 'induction_authorisation'; // Explicitly define the table name

    protected $fillable = ['induction_id', 'user_authorisation_id'];

    public function authorisation()
    {
        return $this->belongsTo(Authorisation::class, 'user_authorisation_id');
    }

    public function induction()
    {
        return $this->belongsTo(Induction::class, 'induction_id');
    }
}
