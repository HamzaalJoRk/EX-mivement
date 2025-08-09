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
        'entry_statement_id',
        'receipt_number',
        'cashier_number',
        'cashier_name',
        'statement_number',
        'driver_name',
        'car_number',
        'fees',
        'additionalFee',
        'violations_total',
        'total_amount',
    ];

    public function entryStatements()
    {
        return $this->belongsTo(EntryStatement::class);
    }

    public function financeBox()
    {
        return $this->belongsTo(FinanceBox::class);
    }

    public function detail()
    {
        return $this->hasOne(FinanceTransactionDetail::class, 'finance_transaction_id');
    }

    /**
     * Boot method to generate serial_number automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            $entry->receipt_number = self::generateSerialNumber();
        });
    }

    /**
     * Generate unique serial number
     */
    private static function generateSerialNumber()
    {
        $latest = self::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        return str_pad($nextId, 6, '0', STR_PAD_LEFT); // مثال: ENT-000001
    }

}
