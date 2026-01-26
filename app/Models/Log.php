<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'username',
        'module',
        'severity',
        'status',
        'action',
        'table_name',
        'record_id',
        'old_data',
        'new_data',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public $timestamps = false;
}
