<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table="staffs";
    protected $casts = [
        'address' => 'array',
    ];

    /**
     * Get the user that owns the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

}
