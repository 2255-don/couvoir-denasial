<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'capacity',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eggBatches()
    {
        return $this->hasMany(EggBatch::class, 'current_unit_id');
    }
}
