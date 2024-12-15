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
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }
    public function progress(): HasMany
    {
        return $this->hasMany(ResponseProgress::class, 'response_id');
    }
}
