<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'dni',
        'address',
        'phone',
    ];

    public function fises(): HasMany {
        return $this->hasMany(Fise::class);
    }

    public function active_fises(): HasMany {
        return $this->fises()->where('is_active', 1);
    }

    public function used_fises(): HasMany {
        return $this->fises()->where('is_active', 0);
    }
}
