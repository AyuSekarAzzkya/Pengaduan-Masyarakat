<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Report;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi: Seorang pengguna bisa memiliki banyak laporan
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    public function staffProvince()
    {
        return $this->hasOne(StaffProvince::class); // Menghubungkan dengan StaffProvince
    }
    public function responses()
    {
        return $this->hasMany(Responses::class, 'staff_id');
    }
    
}
