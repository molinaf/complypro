<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAuthorisation extends Model
{
    use HasFactory;

    protected $table = 'module_authorisation'; // Explicitly define the table name

    protected $fillable = ['module_id', 'user_authorisation_id'];

    public function authorisation()
    {
        return $this->belongsTo(Authorisation::class, 'user_authorisation_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
