<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table="chart_accounts";

    public function category()
    {
        return $this->belongsTo(ChartOfAccountCategory::class, 'category_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(ChartOfAccountSubCategory::class, 'sub_category_id', 'id');
    }
    
     public function ledgerItems()
    {
        return $this->hasMany(LedgerItem::class, 'account_id');
    }
}
