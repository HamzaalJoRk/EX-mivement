<?php

namespace App\Http\Controllers;

use App\Models\FinanceBox;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceBoxController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('startDate', now()->toDateString());
        $endDate = $request->input('endDate', now()->toDateString());
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $boxes = FinanceBox::with([
            'user',
            'transactions' => function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }
        ])->get();

        $boxes->transform(function ($box) {
            if (!$box->relationLoaded('transactions') || is_null($box->transactions)) {
                $box->setRelation('transactions', collect());
            }

            $box->total_amount = $box->transactions->sum('amount');
            $box->transactions_count = $box->transactions->count();

            return $box;
        });

        return view('dashboard.finance.boxes.index', compact(
            'boxes',
            'startDate',
            'endDate'
        ));
    }
}
