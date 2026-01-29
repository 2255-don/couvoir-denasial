<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggBatch extends Model
{
    protected $fillable = [
        'user_id',
        'quantity',
        'loading_date',
        'transfer_date',
        'hatching_date',
        'current_unit_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentUnit()
    {
        return $this->belongsTo(Unit::class, 'current_unit_id');
    }
    
    protected $casts = [
        'loading_date' => 'datetime',
        'transfer_date' => 'datetime',
        'hatching_date' => 'datetime',
    ];
}
