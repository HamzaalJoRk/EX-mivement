<?php

namespace App\Http\Controllers;

use App\Models\FinanceBox;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FinanceTransaction;
use Carbon\Carbon;


class FinanceTransactionController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->financeBox) {
            return view('dashboard.finance.transactions.index', [
                'transactions' => [],
                'total' => 0,
                'start_date' => null,
                'end_date' => null,
            ]);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $user->financeBox->transactions()->orderBy('created_at', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        } else {
            $today = Carbon::now()->toDateString();
            $query->whereDate('created_at', $today);
        }

        $transactions = $query->get();
        $total = $transactions->sum('amount');

        return view('dashboard.finance.transactions.index', compact('transactions', 'total', 'startDate', 'endDate'));
    }

    public function boxTransactions(Request $request, FinanceBox $box)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $user = User::findOrFail($box->user_id);
        
        $query = $user->financeBox->transactions()->orderBy('created_at', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        } else {
            $today = Carbon::now()->toDateString();
            $query->whereDate('created_at', $today);
        }

        $transactions = $query->get();
        $total = $transactions->sum('amount');

        return view('dashboard.finance.transactions.index', compact('transactions', 'total', 'startDate', 'endDate'));
    }
    // public function boxTransactions(Request $request, FinanceBox $box)
    // {
    //     $fromDate = $request->input('from_date');
    //     $toDate = $request->input('to_date');

    //     $transactionsQuery = $box->transactions()->orderBy('created_at', 'desc');

    //     if ($fromDate && $toDate) {
    //         $transactionsQuery->whereBetween('created_at', [$fromDate, $toDate]);
    //     }

    //     $transactions = $transactionsQuery->get();
    //     $total = $transactions->sum('amount');

    //     return view('dashboard.finance.transactions.box', compact('box', 'transactions', 'total', 'fromDate', 'toDate'));
    // }

}
