<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceTransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['finance_transaction_id', 'fee', 'penalty', 'violations_total'];

    public function transaction()
    {
        return $this->belongsTo(FinanceTransaction::class, 'finance_transaction_id');
    }
}
