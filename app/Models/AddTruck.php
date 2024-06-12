<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddTruck extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'value',
        'title'
    ];
}
