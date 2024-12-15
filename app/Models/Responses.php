<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Responses extends Model
{
    use HasFactory;
    protected $fillable = ([
        'report_id',
        'response_status',
        'staff_id'

    ]);
    // Di dalam model Responses
    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    public function progress()
    {
        return $this->hasMany(ResponseProgress::class, 'response_id', 'id');
    }
}
