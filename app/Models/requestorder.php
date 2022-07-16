<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class requestorder extends Model
{
    use HasFactory;

    protected $fillable = [

    'dress_id',
    'customer_id',
    'startdate',
    'enddate',
    'price',
    'state',
    'dayes'
    ];

    protected $table= 'requestorders';

}
