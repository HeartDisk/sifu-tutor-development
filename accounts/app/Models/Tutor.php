<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;
    public function tutorState(){
    return $this->belongsTo(State::class,'state');
    }
    public function tutorCity(){
        return $this->belongsTo(City::class,'city');
        }
    public function tutorpayment(){
        return $this->hasOne(Tutorpayment::class,'tutorID');

    }
}
