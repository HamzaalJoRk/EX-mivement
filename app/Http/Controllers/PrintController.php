<?php

namespace App\Http\Controllers;

use App\Models\FinancialReceipt;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printCard($id)
    {
        $data = FinancialReceipt::findOrFail($id);
        return view('dashboard.entry_statements.FinancialReceiptPrint');
    }
}
