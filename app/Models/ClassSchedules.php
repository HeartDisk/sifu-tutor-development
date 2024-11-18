<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    public function class_schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'tutorID');
    }
    
     public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutorID');
    }

    public function subject()
    {
        return $this->belongsTo(Product::class, 'subjectID');
    }
}
