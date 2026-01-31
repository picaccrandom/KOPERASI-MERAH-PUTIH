<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orderObat extends Model
{
    protected $table = 'order_obats';

    protected $fillable = [
        'pendaftaran_klinik_id',
        'tanggal_order',
        'member_id',
        'user_id',
        'order_body',
        'nominal',
        'status'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function pendaftaranKlinik()
    {
        return $this->belongsTo(PendaftaranKlinik::class, 'pendaftaran_klinik_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getOrderBodyAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setOrderBodyAttribute($value)
    {
        $this->attributes['order_body'] = json_encode($value);
    }

}
