<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseVoucherItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['expense_voucher_id', 'quantity', 'description', 'price','total'];
    public function expenseVoucher()
    {
        return $this->belongsTo(ExpenseVoucher::class);
    }
}
