<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dressimage extends Model
{
    protected $fillable = [
        'dress_id', 'image',
    ];
    use HasFactory;
    public function dress()
    {
        return $this->belongsTo(Dress::class);
    }
}
