<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $table = 'tasks';
    protected $guarded = [];

    protected $allowedSorts = [
        'address',
        'status'
    ];
}
