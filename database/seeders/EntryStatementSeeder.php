<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EntryStatement;

class EntryStatementSeeder extends Seeder
{
    public function run(): void
    {
        EntryStatement::create([
            'date' => now(),
            'car_number' => 'SYP-12345',
            'owner_name' => 'Ali Ahmad',
            'car_type' => 'Van',
            'license_number' => 'LIC12345',
        ]);
    }
}
