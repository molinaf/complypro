<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthorisation extends Model
{
    use HasFactory;

    protected $table = 'user_authorisations';

    protected $fillable = ['application_id', 'authorisation_id', 'status'];

    /**
     * Relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to the Authorisation model.
     */
    public function authorisation()
    {
        return $this->belongsTo(Authorisation::class, 'authorisation_id');
    }

    /**
     * Relationship to the Application model.
     */
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    /**
     * Relationship to the UserAuthRequisite model.
     */
    public function prerequisites()
    {
        return $this->hasMany(UserAuthRequisite::class, 'user_authorisation_id');
    }
}
