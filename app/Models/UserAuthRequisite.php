<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthRequisite extends Model
{
    use HasFactory;

    protected $fillable = ['user_authorisation_id', 'type', 'reference_id', 'status'];

    public function userAuthorisation()
    {
        return $this->belongsTo(UserAuthorisation::class, 'user_authorisation_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'reference_id');
    }

    public function f2f()
    {
        return $this->belongsTo(F2F::class, 'reference_id');
    }

    public function induction()
    {
        return $this->belongsTo(Induction::class, 'reference_id');
    }

    public function license()
    {
        return $this->belongsTo(License::class, 'reference_id');
    }
}
