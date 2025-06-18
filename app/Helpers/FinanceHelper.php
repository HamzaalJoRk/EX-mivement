<?php

namespace App\Helpers;

use App\Models\FinanceBox;
use App\Models\FinanceTransaction;
use Illuminate\Support\Facades\Auth;

class FinanceHelper
{
    /**
     * تسجيل عملية مالية للمستخدم الحالي
     *
     * @param string $description وصف العملية (مثلاً: "تم الدفع")
     * @param string $operationFor العملية لأجل ماذا (مثلاً: "طلب رقم 123")
     * @param float $amount المبلغ
     * @return bool
     */
    /**
     * @return FinanceTransaction|null
     */
    public static function logTransaction(string $description, string $operationFor, float $amount): ?FinanceTransaction
    {
        $user = Auth::user();

        if (!$user || !$user->financeBox) {
            return null;
        }

        return FinanceTransaction::create([
            'finance_box_id' => $user->financeBox->id,
            'amount' => $amount,
            'description' => $description,
            'operation_for' => $operationFor,
        ]);
    }


    /**
     * إحضار رصيد المستخدم الحالي من العمليات (مجموع المبالغ)
     *
     * @return float
     */
    public static function getCurrentBalance(): float
    {
        $user = Auth::user();

        if (!$user || !$user->financeBox) {
            return 0;
        }

        return $user->financeBox->transactions()->sum('amount');
    }
}
