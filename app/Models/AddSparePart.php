<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddSparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'title',
        'value'
    ];
}
