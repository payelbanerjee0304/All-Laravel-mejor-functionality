<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $collection = 'districts';

    protected $fillable = [
        'name', 
        'district_code', 
        'stateId'
    ];
}
