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

    public function entrySearch()
    {
        return view('dashboard.entry_statements.search');
    }

    public function entrySearch_show(Request $request)
    {
        $entry = EntryStatement::where('serial_number', $request->serial_number)->first();
        if (!$entry) {
            return redirect()->back()->with('error', 'لم يتم العثور على البيان.');
        }
        if (auth()->user()->hasRole('Finance') && $entry->completeFinanceExit == 1) {
            return redirect()->back()->with('error', 'تم دفع رسوم الخروج.');
        }
        return redirect()->route('entry_statements.show', $entry->id)->with('success', 'تم العثور على البيان.');
    }


    public function addTime(Request $request, $id)
    {
        $entry = EntryStatement::findOrFail($id);
        $entry->stay_duration = $entry->stay_duration + $request->add_week;
        if ($request->add_week == 12) {
            $entry->stay_fee += 200;
        } else {
            $entry->stay_fee += 50;
        }
        $entry->save();
        EntryStatementLogHelper::log($entry->id, 'تمديد مدة البقاء', ' تم تمديد المدة وهي: ' . $request->add_week . ' اسابيع' . ' وبرقم تسلسلي: ' . $entry->serial_number);
        UserLogHelper::log('دفع رسوم', 'رقم الطلب: ' . $entry->serial_number);
        return redirect()->back()->with('success', 'تم تمديد فترة البقاء بنجاح.');
    }


    public function FinanceExit(Request $request, $id)
    {
        $entry = EntryStatement::findOrFail($id);
        $entry->exit_fee = $request->input('total_dollar');
        $entry->completeFinanceExit = true;
        $entry->save();

        foreach ($entry->violations as $violation) {
            $entry->violations()->updateExistingPivot($violation->id, ['isCompleteFinance' => true]);
        }

        EntryStatementLogHelper::log($entry->id, 'دفع رسوم', 'تم دفع الرسوم وهي: ' . $entry->exit_fee . '$' . ' وبرقم تسلسلي: ' . $entry->serial_number);
        UserLogHelper::log('دفع رسوم', 'رقم الطلب: ' . $entry->serial_number);
        return redirect()->back()->with('success', 'تم دفع المستحقات بنجاح.');
    }


    public function CompleteExit(Request $request)
    {
        $entry = EntryStatement::where('serial_number', $request->serial_number)->first();
        if (!$entry) {
            return redirect()->back()->with('error', 'لم يتم العثور على البيان.');
        }
        if (!$entry->completeFinanceExit) {
            return redirect()->back()->with('error', 'لم يتم دفع رسوم الخروج بعد.');
        }
        if ($entry->is_checked_out) {
            return redirect()->back()->with('error', 'تم تسجيل الخروج من قبل.');
        }
        if (!$entry->exit_border_crossing_id) {
            return redirect()->back()->with('error', 'لم يتم تسجيل الخروج من موظف الجمارك.');
        }
        $entry->is_checked_out = true;
        $entry->save();
        return redirect()->back()->with('success', 'تم تسجيل الخروج للمركبة بنجاح.');
    }

    public function CompleteEnrty(Request $request)
    {
        $entry = EntryStatement::where('serial_number', $request->serial_number)->first();
        if (!$entry) {
            return redirect()->back()->with('error', 'لم يتم العثور على البيان.');
        }
        if (!$entry->completeFinanceEntry) {
            return redirect()->back()->with('error', 'لم يتم دفع رسوم الدخول بعد.');
        }
        if ($entry->is_checked_in) {
            return redirect()->back()->with('error', 'تم تسجيل الدخول من قبل.');
        }
        $entry->is_checked_in = true;
        $entry->save();
        return redirect()->back()->with('success', 'تم تسجيل الدخول للمركبة بنجاح.');
    }


    public function FinanceEntry(Request $request, $id)
    {
        $entry = EntryStatement::findOrFail($id);
        $entry->exit_fee = $request->input('total_entry_dollar');
        $entry->completeFinanceEntry = true;
        $entry->save();

        foreach ($entry->violations as $violation) {
            $violation->pivot->isCompleteFinance = true;
            $violation->pivot->save();
        }


        EntryStatementLogHelper::log($entry->id, 'دفع رسوم', 'تم دفع رسوم الدخول وهي: ' . $entry->exit_fee . '$' . ' وبرقم تسلسلي: ' . $entry->serial_number);
        UserLogHelper::log('دفع رسوم', 'رقم الطلب: ' . $entry->serial_number);
        return redirect()->route('entrySearch')->with('success', 'تم دفع المستحقات بنجاح.');
    }


    public function checkout(Request $request, $id)
    {
        // dd($request->all());
        $entry = EntryStatement::findOrFail($id);

        $entry->checked_out_date = Carbon::now();
        if ($request->has('exit_border_crossing_id')) {
            $entry->exit_border_crossing_id = $request->exit_border_crossing_id;
        } else {
            $entry->exit_border_crossing_id = auth()->user()->border_crossing_id;
        }
        $entry->save();
        EntryStatementLogHelper::log($entry->id, 'تسجيل خروج', 'رقم الطلب: #' . $entry->serial_number);
        UserLogHelper::log('حركة خروج', 'رقم الطلب: ' . $entry->serial_number);
        return redirect()->back()->with('success', 'تمت اضافة حركة الخروج بنجاح.');
    }



    public function addViolation(Request $request, $id)
    {
        $request->validate([
            'violation_id' => 'required|exists:violations,id',
        ]);

        $entry = EntryStatement::findOrFail($id);
        $entry->violations()->attach($request->violation_id);
        UserLogHelper::log('اضافة مخالفة', 'رقم الطلب: ' . $entry->serial_number);
        EntryStatementLogHelper::log($entry->id, 'اضافة مخالفة', 'رقم الطلب: #' . $entry->serial_number);

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

        return view('dashboard.entry_statements.create', compact('carTypes', 'subCarTypes', 'borderCrossings'));
    }


    public function store(EntryStatementRequest $request)
    {
        $validated = $request->validated();

        // dd($validated);
        $car_type = $validated['car_type'];
        $car_number = $validated['car_number'];

        switch ($car_type) {
            case 'سيارات سورية او اردنية او لبنانية':
                $existing = EntryStatement::where('car_number', $car_number)->first();
                $validated['stay_fee'] = $existing ? 10 : 15;
                $validated['stay_duration'] = 0;
                break;
            case 'سيارات غير السورية والاردنية واللبنانية':
                $validated['stay_fee'] = ($validated['stay_duration'] == 12) ? 200 : 50;
                break;
            case 'دراجات نارية':
                $validated['stay_fee'] = ($validated['stay_duration'] == 12) ? 200 : 50;
                break;

            case 'شاحنات وباصات خليجية':
                $validated['stay_fee'] = 50;
                break;
            case 'سيارات سورية':
                $validated['stay_fee'] = 0;
                $validated['completeFinanceEntry'] = 1;
                $validated['stay_duration'] = 0;
                break;
            case 'سيارات لبنانية':
                $existing = EntryStatement::where('car_number', $car_number)->first();
                $validated['stay_fee'] = $existing ? 10 : 15;
                $validated['stay_duration'] = 0;
                break;
            case 'سيارات أردنية':
                $existing = EntryStatement::where('car_number', $car_number)->first();
                $validated['stay_fee'] = $existing ? 10 : 15;
                $validated['stay_duration'] = 0;
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


    public function show($id)
    {
        $entry_statement = EntryStatement::with(['borderCrossing', 'exitBorderCrossing'])->findOrFail($id);

        // فقط المخالفات غير المدفوعة
        $unpaidViolations = $entry_statement->violations()->wherePivot('isCompleteFinance', false)->get();

        // تواريخ
        $createdAt = Carbon::parse($entry_statement->created_at);
        $weeks = $entry_statement->stay_duration;
        $allowedStay = $createdAt->copy()->addDays($weeks * 7);
        $today = Carbon::now();

        // التأخير
        $delayDays = $today->gt($allowedStay) ? $today->diffInDays($allowedStay) : 0;
        $penaltyWeeks = ceil($delayDays / 7);

        // غرامة التأخير
        $penaltyPerWeek = 0;
        $carType = $entry_statement->car_type;

        if ($delayDays > 0) {
            if (in_array($carType, ['سيارات غير السورية والاردنية واللبنانية', 'دراجات نارية'])) {
                $penaltyPerWeek = 110;
            } elseif ($carType == 'شاحنات وباصات خليجية') {
                $penaltyPerWeek = 15;
            }
        }

        $penalty = $penaltyPerWeek * $penaltyWeeks;

        $exit_fee = 5;
        $violations_total = $unpaidViolations->sum('fee');
        $total_exit_dollar = $exit_fee + $penalty + $violations_total;

        $entry_fee = $entry_statement->stay_fee;
        $total_entry_dollar = $entry_statement->stay_fee + $violations_total;

        $violations = Violation::all();
        $borderCrossings = BorderCrossing::all();

        return view('dashboard.entry_statements.show', compact(
            'entry_statement',
            'createdAt',
            'allowedStay',
            'borderCrossings',
            'delayDays',
            'penaltyWeeks',
            'penaltyPerWeek',
            'penalty',
            'exit_fee',
            'entry_fee',
            'violations_total',
            'unpaidViolations',
            'total_exit_dollar',
            'violations',
            'total_entry_dollar'
        ));
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
