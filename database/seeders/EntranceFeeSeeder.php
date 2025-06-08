<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EntranceFee;
use App\Models\EntryStatement;

class EntranceFeeSeeder extends Seeder
{
    public function run(): void
    {
        $entry = EntryStatement::first();

        EntranceFee::create([
            'entry_statement_id' => $entry->id,
            'duration' => '2 hours',
            'fees' => 1500,
        ]);
    }
}
