<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProvince extends Model
{
    use HasFactory;
    protected $fillable = ([
        'province',
        'user_id'
    ]);
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
