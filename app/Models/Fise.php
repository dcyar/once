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
        'used_at',
    ];

    protected function casts(): array {
        return [
            'expiration_date' => 'date:Y-m-d',
            'used_at'         => 'datetime',
        ];
    }

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }
}
