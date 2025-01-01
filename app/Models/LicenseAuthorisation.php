<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenseAuthorisation extends Model
{
    use HasFactory;

    protected $table = 'license_authorisation'; // Explicitly define the table name

    protected $fillable = ['license_id', 'user_authorisation_id'];

    public function authorisation()
    {
        return $this->belongsTo(Authorisation::class, 'user_authorisation_id');
    }

    public function license()
    {
        return $this->belongsTo(License::class, 'license_id');
    }
}
