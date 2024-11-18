<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorVoucherItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['tutor_voucher_id', 'quantity', 'description', 'price','total'];
    public function customerReceiveVoucher()
    {
        return $this->belongsTo(TutorVoucher::class);
    }
}
