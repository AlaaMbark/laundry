<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'email',
        'code',
        'token',
        'created_at',
    ];
}