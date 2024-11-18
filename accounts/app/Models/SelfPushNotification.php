<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfPushNotification extends Model
{
    use HasFactory;
    public $fillable = ['page','subject','message','time','type','date','remark'];
}
