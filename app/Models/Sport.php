<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sport extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function equipment() : BelongsToMany {
        return $this->belongsToMany('\App\Models\Equipment');
    }
}
