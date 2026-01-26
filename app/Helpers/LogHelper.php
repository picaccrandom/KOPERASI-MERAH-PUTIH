<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\Log;

if (!function_exists('writeLog')) {
    function writeLog(
        string $module,
        string $action,
        string $table,
        $recordId = null,
        $oldData = null,
        $newData = null,
        string $description = null,
        string $severity = 'info',
        string $status = 'success'
    ) {
        Log::create([
            'user_id'    => Auth::id(),
            'username'   => Auth::user()->username ?? null,
            'module'     => $module,
            'action'     => $action,
            'table_name' => $table,
            'record_id'  => $recordId,
            'old_data'   => $oldData,
            'new_data'   => $newData,
            'description'=> $description,
            'severity'   => $severity,
            'status'     => $status,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
