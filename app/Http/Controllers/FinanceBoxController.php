<?php

namespace App\Http\Controllers;

use App\Models\FinanceBox;
use Illuminate\Http\Request;

class FinanceBoxController extends Controller
{
public function index(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $boxes = FinanceBox::with(['user', 'transactions'])->get();

    $boxes->map(function ($box) use ($startDate, $endDate) {
        $transactions = $box->transactions;

        if ($startDate && $endDate) {
            $transactions = $transactions->filter(function ($t) use ($startDate, $endDate) {
                return $t->created_at->between($startDate, $endDate);
            });
        }

        $box->filtered_transactions = $transactions;
        $box->total_amount = $transactions->sum('amount');

        return $box;
    });

    return view('dashboard.finance.boxes.index', compact('boxes', 'startDate', 'endDate'));
}

}
