<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;
    
     protected $fillable = [
        'ip', 'method', 'url', 'headers', 'body', 'status', 'response_content',
    ];
}
