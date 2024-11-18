<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReceiveVoucherItem extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['customer_receive_voucher_id', 'quantity', 'description', 'price','total'];
    public function customerReceiveVoucher()
    {
        return $this->belongsTo(CustomerReceiveVoucher::class);
    }

}
