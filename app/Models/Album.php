<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'albums';
    protected $guarded = [];

    public function performer()
    {
        return $this->belongsTo(Performer::class, 'performer_id', 'id');
    }
}
