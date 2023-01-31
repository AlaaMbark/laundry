<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =[
        'status',
        'readable',
        'acceptable',
        'canceled_from',
        'canceled',
        'reason_cancellation',
        'grille_id',
        'user_id',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function grille(){
        return $this->hasOne(User::class, 'grille_id');
    }
}
