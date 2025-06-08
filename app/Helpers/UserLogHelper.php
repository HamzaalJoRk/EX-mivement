<?php
namespace App\Helpers;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserLogHelper
{
    public static function log($action, $details = null)
    {
        if (!Auth::check())
            return;

        UserLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'details' => is_array($details) ? json_encode($details) : $details,
            'ip' => Request::ip(),
            'device' => request()->header('User-Agent'),
        ]);
    }
}
