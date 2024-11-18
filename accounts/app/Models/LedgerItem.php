<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerItem extends Model
{
    use HasFactory;
    
    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
    
}
