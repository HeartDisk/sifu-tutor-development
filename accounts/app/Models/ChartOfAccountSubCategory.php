<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccountSubCategory extends Model
{
    use HasFactory;
    // Define the belongsTo relationship
    public function category()
    {
        return $this->belongsTo(ChartOfAccountCategory::class, 'category_id', 'id');
    }
}
