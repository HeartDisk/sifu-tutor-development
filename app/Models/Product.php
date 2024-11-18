<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
     public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }
    
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'services' => 'array',
        
    ];
}
