<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntryStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_type',
        'driver_name',
        'car_number',
        'stay_duration',
        'stay_fee',
        'serial_number',
        'is_checked_out',
        'is_checked_in',
        'checked_out_date',
        'exit_fee',
        'border_crossing_id',
        'exit_border_crossing_id',
        'car_nationality',
        'car_brand',
        'completeFinanceExit',
        'completeFinanceEntry',
    ];

    // EntryStatement.php
    public function violations()
    {
        return $this->belongsToMany(Violation::class, 'entry_statement_violation')
            ->withPivot('isCompleteFinance');
    }



    public function borderCrossing()
    {
        return $this->belongsTo(BorderCrossing::class, 'border_crossing_id');
    }

    public function exitBorderCrossing()
    {
        return $this->belongsTo(BorderCrossing::class, 'exit_border_crossing_id');
    }


    /**
     * Boot method to generate serial_number automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            $entry->serial_number = self::generateSerialNumber();
        });
    }

    /**
     * Generate unique serial number
     */
    private static function generateSerialNumber()
    {
        $latest = self::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        return 'NBS-' . str_pad($nextId, 6, '0', STR_PAD_LEFT); // مثال: ENT-000001
    }
}
