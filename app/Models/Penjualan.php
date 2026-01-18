<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class Penjualan extends Model {
        protected $fillable = ['no_invoice', 'member_id', 'total_harga', 'metode_bayar'];

        public function details() {
            return $this->hasMany(PenjualanDetail::class);
        }
    }

