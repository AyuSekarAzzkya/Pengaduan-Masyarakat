<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'description',
        'type',
        'province',
        'regency',
        'subdistrict',
        'village',
        'voting',
        'viewers',
        'image',
        'statement',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Laravel secara otomatis menangani konversi JSON, 
    // jadi Anda tidak perlu menambahkan accessor dan mutator lagi.
    // Namun, jika ingin tetap menambahkan accessor & mutator, berikut adalah cara yang benar:

    // Accessor untuk kolom 'voting' yang otomatis mengembalikan array atau objek.
    public function getVotingAttribute($value)
    {
        return json_decode($value, true);  // Bisa dihilangkan, karena Laravel menangani ini
    }

    // Mutator untuk menyimpan 'voting' dalam format JSON
    public function setVotingAttribute($value)
    {
        $this->attributes['voting'] = json_encode($value); // Menyimpan dalam format JSON
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Responses::class, 'report_id', 'id');
    }

    // Relasi ke ResponseProgress (riwayat respons staff)
    public function progress(): HasMany
    {
        return $this->hasMany(ResponseProgress::class);
    }
}
