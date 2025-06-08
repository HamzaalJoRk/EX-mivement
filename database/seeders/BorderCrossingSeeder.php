<?php

namespace Database\Seeders;

use App\Models\BorderCrossing;
use Illuminate\Database\Seeder;
use App\Models\ExitStatement;
use App\Models\EntryStatement;

class BorderCrossingSeeder extends Seeder
{
    public function run(): void
    {

        $borderCrossings = [
            'معبر الراعي',
            'معبر باب الهوى',
            'معبر باب السلامة',
            'معبر نصيب',
            'معبر جديدة يابوس',
            'معبر البوكمال',
        ];

        foreach ($borderCrossings as $borderCrossing) {
            BorderCrossing::updateOrCreate(['name' => $borderCrossing]);
        }
    }
}
