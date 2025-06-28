<?php

namespace App\Helpers;

use App\Models\FinanceBox;
use App\Models\FinanceTransaction;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;

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
    public static function logTransaction(
        int $entry_statement_id,
        string $description,
        string $operationFor,
        float $amount,
        string $statement_number,
        string $driver_name,
        string $car_number,
        float $fees,
        float $additionalFee,
        float $violations_total,
    ): ?FinanceTransaction {
        $user = Auth::user();

        if (!$user || !$user->financeBox) {
            return null;
        }

        return FinanceTransaction::create([
            'entry_statement_id' => $entry_statement_id,
            'finance_box_id' => $user->financeBox->id,
            'amount' => $amount,
            'description' => $description,
            'operation_for' => $operationFor,
            'cashier_number' => $user->financeBox->number,
            'cashier_name' => $user->name,
            'statement_number' => $statement_number,
            'driver_name' => $driver_name,
            'car_number' => $car_number,
            'fees' => $fees,
            'additionalFee' => $additionalFee,
            'total_amount' => $amount,
            'violations_total' => $violations_total
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
