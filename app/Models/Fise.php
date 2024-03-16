<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fise extends Model {
    use HasFactory;

    protected $fillable = [
        'client_id',
        'code',
        'amount',
        'expiration_date',
        'is_active',
    ];

    protected function casts(): array {
        return [
            'expiration_date' => 'date:Y-m-d',
            'is_active'       => 'boolean',
        ];
    }

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }
}
