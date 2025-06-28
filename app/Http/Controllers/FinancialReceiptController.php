<?php

namespace App\Http\Controllers;

use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialReceiptController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // فلترة التاريخ
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transactions = FinanceTransaction::query();

        // إذا لم يكن المستخدم Admin نفلتر بحسب صندوقه فقط
        if (!$user->hasRole('Admin')) {
            if (!$user->financeBox) {
                return back()->with('error', 'لا تملك صندوق مالي.');
            }
            $transactions->where('finance_box_id', $user->financeBox->id);
        }

        // فلترة بالتاريخ إذا وُجدت
        if ($startDate) {
            $transactions->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $transactions->whereDate('created_at', '<=', $endDate);
        }

        $transactions = $transactions->latest()->with('detail')->paginate(10);

        // العمليات الحسابية
        $total = $transactions->sum('amount');
        $totalFees = $transactions->sum('fees');
        $totalPenalties = $transactions->sum(fn($t) => optional($t->detail)->penalty ?? 0);
        $totalViolations = $transactions->sum(fn($t) => optional($t->detail)->violations_total ?? 0);

        return view('dashboard.finance.receipts.index', [
            'transactions' => $transactions,
            'total' => $total,
            'totalFees' => $totalFees,
            'totalPenalties' => $totalPenalties,
            'totalViolations' => $totalViolations,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'box' => $user->financeBox 
        ]);
    }

}
