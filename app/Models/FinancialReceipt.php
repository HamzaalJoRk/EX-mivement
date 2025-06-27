<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_statement_id',
        'cashier_number',
        'cashier_name',
        'receipt_number',
        'statement_number',
        'driver_name',
        'car_number',
        'fees',
        'additionalFee',
        'violations_total',
        'total_amount',
    ];


    public function financialReceipts()
    {
        return $this->hasMany(FinancialReceipt::class);
    }

}
