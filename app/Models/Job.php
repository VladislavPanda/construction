<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $table = 'jobs';
    protected $guarded = [];

    protected $allowedSorts = [
        'description',
        'status'
    ];
}
