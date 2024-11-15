<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class UploadExcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'state',
        'district',
        'city',
        'language',
        'profile_picture',
        'images',
        'date_of_registration',
    ];
}
