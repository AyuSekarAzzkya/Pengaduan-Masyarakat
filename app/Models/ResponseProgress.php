<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponseProgress extends Model
{
    use HasFactory;

    protected $fillable = ([
        'responses_id',
        'histories'
    ]);
    protected $casts = [
        'histories' => 'array',  // Untuk memastikan Laravel mengonversi data ke array jika kolom bertipe JSON
    ];
    // Di dalam model ResponseProgress
    public function response()
    {
        return $this->belongsTo(Responses::class, 'response_id', 'id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
