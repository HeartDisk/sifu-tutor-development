<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReceiveVoucher extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['customer_id', 'note', 'reference_no', 'amount'];

    public function items()
    {
        return $this->hasMany(CustomerReceiveVoucherItem::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');

    }

}
