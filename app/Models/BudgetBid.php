<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetBid extends Model
{
    use HasFactory;
    
    protected $table = 'budget_bids';
    protected $guarded = [];
}
