<?php

namespace App\Http\Controllers;

use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;


class PrintController extends Controller
{
    public function printCard($id)
    {
        $data = FinanceTransaction::findOrFail($id);

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('ar');

        $totalInWords = $numberTransformer->toWords(intval($data->total_amount));

        return view('dashboard.entry_statements.FinancialReceiptPrint', [
            'data' => $data,
            'totalInWords' => $totalInWords . ' دولاراً فقط'
        ]);
    }
}
