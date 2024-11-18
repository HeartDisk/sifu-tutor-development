<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalLedger extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $cast = ['chart_of_accounts' => 'array'];
}
