<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class AnswerSubmission extends Model
{
    use HasFactory;
    protected $connection = 'mongodb'; // Specify MongoDB connection
    protected $collection = 'answer_submissions'; // Specify collection name
    
    protected $fillable = [
        'taskpoints',
        'subtask',
        // Do not include _id if MongoDB handles it
    ];
    
    // Optional: Specify the primary key if needed
    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';
}
