<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Performer extends Model
{
    use HasFactory;

    protected $table = 'performers';
    protected $guarded = [];

    public function albums(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Album::class, 'id', 'performer_id');
    }
}
