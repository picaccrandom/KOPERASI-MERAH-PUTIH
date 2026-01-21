<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KreditAnggota extends Model
{
    use HasFactory; 

    protected $table = 'kredit_anggotas';

    protected $fillable = [
        'member_id',
        'limit',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
