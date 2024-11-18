<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentList extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function studentable()
    {
        return $this->morphTo();
    }
}
