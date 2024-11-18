<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $fillable=["*"];

    public function ledgerItems()
    {
        return $this->hasMany(LedgerItem::class);
    }

}
