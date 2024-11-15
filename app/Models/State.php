<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'states';

    protected $fillable = [
        'name',
        'state_code',
    ];
}
