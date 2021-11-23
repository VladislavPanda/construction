<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $table = 'projects';
    protected $guarded = [];

    protected $allowedSorts = [
        'end_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
