<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorisation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_authorisation');
    }
    
    public function f2fs()
    {
        return $this->belongsToMany(F2f::class, 'f2f_authorisation', 'authorisation_id', 'f2f_id');
    }

    
    public function inductions()
    {
        return $this->belongsToMany(Induction::class, 'induction_authorisation');
    }
    
    public function licenses()
    {
        return $this->belongsToMany(License::class, 'license_authorisation');
    }
    
    public function userAuthorisations()
    {
        return $this->hasMany(UserAuthorisation::class, 'authorisation_id');
    }

    public function prerequisites()
    {
        return $this->hasMany(UserAuthRequisite::class); // Assuming prerequisites are stored in a `prerequisites` table
    }
    

}
