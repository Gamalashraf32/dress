<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dress extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id',
        'category_id',
        'description',
        'size',
        'color',
        'price_per_day',
        'status'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function image()
    {
        return $this->hasMany(Dressimage::class);
    }
}
