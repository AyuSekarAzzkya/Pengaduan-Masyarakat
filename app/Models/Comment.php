<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    use HasFactory;

    protected $fillable = ['comment', 'user_id', 'report_id'];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan report
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
