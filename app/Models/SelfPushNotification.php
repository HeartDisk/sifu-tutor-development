<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfPushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'subject',
        'message',
        'time',
        'type',
        'date',
        'remark',
        'days', // Add this line
        'monthly_date' // Add this line
    ];

    // If days is stored as JSON, cast it as an array
    protected $casts = [
        'days' => 'array',
    ];
}
