<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'finance_box_id',
        'amount',
        'description',
        'operation_for',
    ];

    public function financeBox()
    {
        return $this->belongsTo(FinanceBox::class);
    }
}
