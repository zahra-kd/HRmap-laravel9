<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'nationality',
        'national_id',
        'picture',
        'phone',
        'email',
        'password',
        'situation',
        'spouse_name',
        'children',
        'job_title',
        'departement_id',
        'date_of_joining',
        'date_of-leaving',
        'role',
        'remember_token',
        'created_at',
        'updated_at',
        'status',
    ];

    public function departement(){
        return $this->belongsTo(Departement::class);
    }

    public function document(){
        return $this->hasMany(Document::class);
    }

    public function leave(){
        return $this->hasMany(Leave::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];
}
