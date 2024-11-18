<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorVoucher extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['tutor_id', 'note', 'type', 'reference_no', 'amount'];

    public function items()
    {
        return $this->hasMany(TutorVoucherItem::class);
    }

//    public function getCreatedAtAttribute($date)
//    {
//        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
//    }

    public function getAmountAttribute($amount)
    {
        return "RM ".number_format($amount,2);
    }

//    public function getUpdatedAtAttribute($date)
//    {
//        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
//
//    }
}
