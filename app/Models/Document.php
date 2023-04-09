<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'request_date',
        'description',
        'status',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function employee(){
        return $this->belongsTo(User::class, "user_id");
    }
}
