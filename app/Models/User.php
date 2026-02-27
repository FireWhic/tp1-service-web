<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone'
    ];

    public function rentals() : HasMany {
        return $this->hasMany('\App\Models\Rental');
    }

    public function reviews() : HasMany {
        return $this->hasMany('\App\Models\Review');
    }
}

