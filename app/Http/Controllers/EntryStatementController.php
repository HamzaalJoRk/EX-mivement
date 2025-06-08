<?php

namespace App\Http\Controllers;

use App\Helpers\EntryStatementLogHelper;
use App\Helpers\UserLogHelper;
use App\Http\Requests\EntryStatementRequest;
use App\Models\BorderCrossing;
use App\Models\EntryStatement;
use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntryStatementController extends Controller
{
    /**
     * Display a listing of the entry statements.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $entries = EntryStatement::query();

        if ($startDate && $endDate) {
            $entries->whereBetween('created_at', [$startDate, $endDate]);
        }

        $entries = $entries->orderBy('created_at', 'desc')->get();

        $today = now()->toDateString();

        $todayStats = EntryStatement::whereDate('created_at', $today);

        $totalEntryFee = $todayStats->sum('stay_fee');
        $totalExitFee = $todayStats->sum('exit_fee');
        $entryCount = $todayStats->count();

        return view('dashboard.entry_statements.index', [
            'entries' => $entries,
            'totalEntryFee' => $totalEntryFee,
            'totalExitFee' => $totalExitFee,
            'entryCount' => $entryCount,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }


    public function FinanceExit(Request $request, $id)
    {
        $entry = EntryStatement::findOrFail($id);
        $entry->exit_fee = $request->input('total_dollar');
        $entry->completeFinanceExit = true;
        $entry->save();
        EntryStatementLogHelper::log($entry->id, 'دفع رسوم', 'تم دفع الرسوم وهي: ' . $entry->exit_fee . '$' . 'وبرقم تسلسلي: ' . $entry->serial_number);

        return redirect()->route('entry_statements.index')->with('success', 'تم دفع المستحقات بنجاح.');
    }

    public function checkout(Request $request, $id)
    {
        $entry = EntryStatement::findOrFail($id);

        $entry->is_checked_out = true;
        $entry->checked_out_date = Carbon::now();
        if ($request->has('exit_border_crossing_id')) {
            $entry->exit_border_crossing_id = $request->exit_border_crossing_id;
        } else {
            $entry->exit_border_crossing_id = auth()->user()->border_crossing_id;
        }
        $entry->save();
        EntryStatementLogHelper::log($entry->id, 'تسجيل خروج', 'رقم الطلب: #' . $entry->serial_number);

        return redirect()->route('entry_statements.index')->with('success', 'تمت اضافة حركة الخروج بنجاح.');
    }



    public function addViolation(Request $request, $id)
    {
        $request->validate([
            'violation_id' => 'required|exists:violations,id',
        ]);

        $entry = EntryStatement::findOrFail($id);
        $entry->violations()->attach($request->violation_id);

        return redirect()->back()->with('success', 'تمت إضافة المخالفة بنجاح.');
    }


    public function create()
    {
        $carTypes = [
            'سيارات سورية',
            'سيارات لبنانية',
            'سيارات أردنية',
            'سيارات غير السورية والاردنية واللبنانية',
            'دراجات نارية',
            'شاحنات أو باصات خليجية',
        ];

        $subCarTypes = [
            'سيارات سورية او اردنية او لبنانية' => [
                'سورية مالكها سوري لديه اقامة سورية',
                'سورية مالكها غير سوري',
                'اردنية او لبنانية',
            ],
        ];

        $borderCrossings = BorderCrossing::all();

        return view('dashboard.entry_statements.create', compact('carTypes', 'subCarTypes', 'borderCrossings'));
    }


    public function store(EntryStatementRequest $request)
    {
        $validated = $request->validated();

        $car_type = $validated['car_type'];
        $car_number = $validated['car_number'];

        switch ($car_type) {
            case 'سيارات سورية او اردنية او لبنانية':
                $existing = EntryStatement::where('car_number', $car_number)->first();
                $validated['stay_fee'] = $existing ? 10 : 15;
                $validated['stay_duration'] = 0;

                if (!empty($request->sub_car_type)) {
                    $validated['car_type'] = $car_type . ' - ' . $request->sub_car_type;
                }
                break;

            case 'سيارات غير السورية والاردنية واللبنانية':
            case 'دراجات نارية':
                $validated['stay_fee'] = ($validated['stay_duration'] == 12) ? 200 : 50;
                break;

            case 'شاحنات وباصات خليجية':
                $validated['stay_fee'] = 50;
                break;
        }

        $entry = EntryStatement::create($validated);

        EntryStatementLogHelper::log($entry->id, 'إنشاء', 'رقم الطلب: #' . $entry->serial_number);

        UserLogHelper::log('انشاء حركة دخول', 'رقم الطلب: ' . $entry->serial_number);


        return redirect()->route('entry_statements.show', $entry->id)->with('success', 'تمت الإضافة بنجاح.');
    }



    public function update(EntryStatementRequest $request, EntryStatement $entry_statement)
    {
        $entry_statement->update($request->validated());
        return redirect()->route('dashboard')->with('success', 'تم التحديث بنجاح.');
    }


    public function show(EntryStatement $entry_statement)
    {
        $entry_statement = EntryStatement::with('violations')->findOrFail($entry_statement->id);
        $violations = Violation::all();
        $borderCrossings = BorderCrossing::all();
        return view('dashboard.entry_statements.show', compact('entry_statement', 'violations'));
    }


    public function edit(EntryStatement $entryStatement)
    {
        $carTypes = [
            'سيارات سورية او اردنية او لبنانية',
            'سيارات غير المذكورة',
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

        return view('dashboard.entry_statements.edit', compact('entryStatement', 'carTypes', 'subCarTypes', 'borderCrossings'));
    }


    public function destroy(EntryStatement $entry_statement)
    {
        $entry_statement->delete();

        return redirect()->route('dashboard')->with('success', 'تم الحذف بنجاح.');
    }
}
