<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceBox extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    /**
     * Boot method to generate number automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            $entry->number = self::generateSerialNumber();
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
