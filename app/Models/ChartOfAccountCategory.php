<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccountCategory extends Model
{
    use HasFactory;
    public function subCategories()
    {
        return $this->hasMany(ChartOfAccountSubCategory::class, 'category_id', 'id');
    }
}
