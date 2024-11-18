<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankVoucherItem extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $fillable = ['bank_voucher_id', 'quantity', 'description', 'price','total'];
    
    public function bankVoucher()
    {
        return $this->belongsTo(bankVoucher::class);
    }
}
