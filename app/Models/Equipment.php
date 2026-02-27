<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'daily_price',
        'category_id'
    ];

    public function sports() : BelongsToMany {
        return $this->belongsToMany('\App\Models\Sport');
    }

    public function category() : BelongsTo{
        return $this->belongsTo('\App\Models\Category');
    }

    public function rentals() : HasMany{
        return $this->hasMany('\App\Models\Rental');
    }
}
