<?php

namespace App\Http\Controllers;

use App\Helpers\EntryStatementLogHelper;
use App\Helpers\UserLogHelper;
use App\Http\Requests\EntryStatementRequest;
use App\Models\BorderCrossing;
use App\Models\EntryCard;
use App\Models\ExitStatement;
use App\Models\EntryStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ExitStatementController extends Controller
{
    public function create()
    {
        $carTypes = [
            'سيارات سورية',
            'سيارات لبنانية',
            'سيارات أردنية',
            'سيارات غير السورية والاردنية واللبنانية',
            'دراجات نارية',
            'شاحنات وباصات خليجية',
        ];

        $subCarTypes = [
            'سيارات سورية او اردنية او لبنانية' => [
                'سورية مالكها سوري لديه اقامة سورية',
                'سورية مالكها غير سوري',
                'اردنية او لبنانية',
            ],
        ];

        $borderCrossings = BorderCrossing::all();
        return view('dashboard.exit_statements.create', compact('carTypes', 'subCarTypes', 'borderCrossings'));
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'car_type' => 'required|string|max:255',
                'driver_name' => 'required|string|max:255',
                'car_number' => 'required|string|max:255',
                'car_brand' => 'nullable|string|max:255',
                'car_nationality' => 'required|string|max:255',
                'border_crossing_id' => 'required|exists:border_crossings,id',
                'stay_duration' => 'nullable|numeric|min:0',
                'stay_fee' => 'nullable|numeric|min:0',
                'is_checked_out' => 'nullable|boolean',
                'exit_fee' => 'nullable|numeric|min:0',
                'owner_name' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:255',
            ]);

            $validated['stay_fee'] = 0;
            $validated['stay_duration'] = 0;
            $validated['completeFinanceEntry'] = true;
            $validated['is_checked_in'] = true;

            if (empty($validated['car_brand'])) {
                $validated['car_brand'] = 'none';
            }

            $entry = EntryStatement::create($validated);

            EntryCard::create([
                'entry_statement_id' => $entry->id,
                'owner_name' => $validated['owner_name'] ?? 'اسم غير معروف',
                'car_number' => $validated['car_number'],
                'car_type' => $validated['car_type'],
                'stay_duration' => 'لا يوجد',
                'entry_date' => now()->toDateString(),
                'exit_date' => now()->toDateString(),
                'qr_code' => null,
            ]);

            EntryStatementLogHelper::log($entry->id, 'إنشاء', 'رقم الطلب: #' . $entry->serial_number);
            UserLogHelper::log('انشاء حركة خروج', 'رقم الطلب: ' . $entry->serial_number);

            return redirect()->route('entry_statements.show', Crypt::encrypt($entry->id))
                ->with('success', 'تمت الإضافة بنجاح.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error('فشل في إنشاء حركة خروج', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'حدث خطأ غير متوقع أثناء الإضافة، يرجى المحاولة لاحقاً.');
        }
    }

    public function createByBook(Request $request)
    {
        return view('dashboard.exit_statements.book_create');
    }

    public function searchByBook(Request $request)
    {
        $book_number = $request->get('book_number');

        $foundEntry = EntryStatement::where('book_number', $book_number)->first();

        if (!$foundEntry) {
            return redirect()->back()->with('error', 'لم يتم العثور على بيانات بهذا الدفتر.');
        }

        return redirect()->route('entry_statements.show', $foundEntry->id)
            ->with('success', 'تمت الإضافة بنجاح.');
    }


    public function storeFromBook(Request $request)
    {
        $book_number = $request->input('book_number');
        $existing = EntryStatement::where('book_number', $book_number)->firstOrFail();
        if ($existing->car_type == 'سيارات سورية') {
            $stay_fee = 0;
        } else {
            if ($existing->book_type == 'خاص') {
                $stay_fee = 10;
            } elseif ($existing->book_type == 'عام') {
                $stay_fee = 0;
            }
        }

        $newEntry = EntryStatement::create([
            'car_type' => $existing->car_type,
            'driver_name' => $existing->driver_name,
            'car_number' => $existing->car_number,
            'car_brand' => $existing->car_brand,
            'car_nationality' => $existing->car_nationality,
            'book_number' => $existing->book_number,
            'book_type' => $existing->book_type,
            'border_crossing_id' => auth()->user()->border_crossing_id,
            'stay_duration' => $existing->stay_duration,
            'stay_fee' => $stay_fee,
            'type' => 'دخول وخروج',
        ]);

        EntryCard::create([
            'entry_statement_id' => $newEntry->id,
            'owner_name' => $existing->driver_name,
            'car_number' => $existing->car_number,
            'car_type' => $existing->car_type,
            'stay_duration' => $existing->stay_duration . 'شهر',
            'entry_date' => now()->toDateString(),
            'exit_date' => now()->addMonths($existing->stay_duration ?? 1)->toDateString(),
            'qr_code' => null,
        ]);

        EntryStatementLogHelper::log($newEntry->id, 'إنشاء', 'رقم الطلب: #' . $newEntry->serial_number);
        UserLogHelper::log('انشاء حركة دخول جديدة من رقم دفتر', 'رقم الطلب: ' . $newEntry->serial_number);

        return redirect()->route('entry_statements.show', $newEntry->id)->with('success', 'تم إنشاء الحركة بنجاح من رقم الدفتر.');
    }
}
