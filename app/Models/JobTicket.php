<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTicket extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'subjects' => 'array',
        'students'=>'array',
        'student_address'=>'array',
    ];

    public function customer()
    {
        return $this->morphOne('App\Models\Customer', 'customerable');
        // return $this->belongsTo(Customer::class, 'id', 'student_id');
    }
}
