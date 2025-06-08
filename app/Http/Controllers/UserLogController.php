<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    public function userLogs(User $user)
    {
        $logs = UserLog::where('user_id', $user->id)->latest()->paginate(20);
        return view('dashboard.logs.user_logs', compact('user', 'logs'));
    }
}
