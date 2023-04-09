<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_type',
        'start_date',
        'end_date',
        'days',
        'status',
        'description',
        'user_id',
        'created_at',
        'updated_at',
        'file',
    ];

    public function employee(){
        return $this->belongsTo(User::class, "user_id");
    }
}
