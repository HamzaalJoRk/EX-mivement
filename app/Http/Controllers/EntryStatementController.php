<?php

namespace App\Http\Controllers;

use App\Helpers\EntryStatementLogHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\UserLogHelper;
use App\Http\Requests\EntryStatementRequest;
use App\Models\BorderCrossing;
use App\Models\EntryCard;
use App\Models\EntryStatement;
use App\Models\FinanceTransactionDetail;
use App\Models\FinancialReceipt;
use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EntryStatementController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('startDate', now()->toDateString());
        $endDate = $request->input('endDate', now()->toDateString());

        $entries = EntryStatement::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])->orderBy('created_at', 'desc')->get();

        $totalEntryFee = $entries->sum('stay_fee');
        $totalExitFee = $entries->sum('exit_fee');
        $entryCount = $entries->count();

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
        if (auth()->user()->hasRole('Finance') && $entry->completeFinanceEntry && !$entry->is_checked_in) {
            return redirect()->back()->with('error', 'لم يتم الدخول بعد.');
        }

        return redirect()->route('entry_statements.show', Crypt::encrypt($entry->id))->with('success', 'تم العثور على البيان.');
    }


    public function FinanceExit(Request $request, $id)
    {
        $entry = EntryStatement::findOrFail($id);
        $entry->exit_fee = $request->input('total_exit_dollar');
        $entry->completeFinanceExit = true;
        $entry->save();

        foreach ($entry->violations as $violation) {
            $entry->violations()->updateExistingPivot($violation->id, ['isCompleteFinance' => true]);
        }

        foreach ($entry->additionalFees as $fee) {
            $fee->isCompleteFinance = true;
            $fee->save();
        }

        $transaction = FinanceHelper::logTransaction(
            $entry->id,
            'دفع رسوم خروج للحركة ' . $entry->serial_number,
            'دفع رسوم خروج',
            $entry->exit_fee,
            $entry->serial_number,
            $entry->driver_name,
            $entry->car_number,
            $request->exit_fee,
            $request->additional_fees_total,
            $request->violations_total + $request->penalty,
        );

        FinanceTransactionDetail::create([
            'finance_transaction_id' => $transaction->id,
            'fee' => $request->exit_fee + $request->additional_fees_total,
            'penalty' => $request->penalty,
            'violations_total' => $request->violations_total,
        ]);

        EntryStatementLogHelper::log(
            $entry->id,
            'دفع رسوم',
            'تم دفع الرسوم وهي: ' . $entry->exit_fee . '$' . ' وبرقم تسلسلي: ' . $entry->serial_number
        );

        UserLogHelper::log('دفع رسوم', 'رقم الطلب: ' . $entry->serial_number);

        return redirect()->route('print.card', $transaction->id)->with('success', 'تم دفع المستحقات بنجاح.');
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
        $entry->stay_fee = $request->input('total_entry_dollar');
        $entry->completeFinanceEntry = true;
        $entry->save();

        $user = auth()->user();

        foreach ($entry->violations as $violation) {
            $violation->pivot->isCompleteFinance = true;
            $violation->pivot->save();
        }

        foreach ($entry->additionalFees as $fee) {
            $fee->isCompleteFinance = true;
            $fee->save();
        }

        $transaction = FinanceHelper::logTransaction(
            $entry->id,
            'دفع رسوم دخول للحركة ' . $entry->serial_number,
            'دفع رسوم دخول',
            $entry->stay_fee,

            $entry->serial_number,
            $entry->driver_name,
            $entry->car_number,
            $request->entry_fee,
            $request->additional_fees_total,
            $request->violations_total,
        );

        FinanceTransactionDetail::create([
            'finance_transaction_id' => $transaction->id,
            'fee' => $request->entry_fee + $request->additional_fees_total,
            'penalty' => 0,
            'violations_total' => $request->violations_total,
        ]);

        EntryStatementLogHelper::log($entry->id, 'دفع رسوم', 'تم دفع رسوم الدخول وهي: ' . $entry->exit_fee . '$' . ' وبرقم تسلسلي: ' . $entry->serial_number);
        UserLogHelper::log('دفع رسوم', 'رقم الطلب: ' . $entry->serial_number);
        return redirect()->route('print.card', $transaction->id)->with('success', 'تم دفع المستحقات بنجاح.');
    }

    public function checkout(Request $request, $id)
    {
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
        $entry->save();
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


    public function addTime(Request $request, $id)
    {
        try {
            $oldEntry = EntryStatement::findOrFail($id);

            if ($request->delayDays > 0) {
                $newDate = now()->toDateString();
            } else {
                $newDate = $request->allowedStay;
            }
            // dd($newDate);


            $data = $oldEntry->toArray();
            unset(
                $data['id'],
                $data['created_at'],
                $data['updated_at'],
                $data['is_checked_out'],
                $data['completeFinanceExit'],
                $data['checked_out_date'],
                $data['exit_fee'],
                $data['exit_border_crossing_id '],
                $data['is_checked_in'],
                $data['completeFinanceEntry'],
            );

            $data['stay_duration'] = $request->add_week;

            $car_type = $oldEntry->car_type;

            switch ($car_type) {
                case 'سيارات غير السورية والاردنية واللبنانية':
                    if ($request->add_week == 12) {
                        $data['stay_fee'] = 200;
                    } else {
                        $data['stay_fee'] = 50;
                    }
                case 'دراجات نارية':
                    if ($request->add_week == 12) {
                        $data['stay_fee'] = 200;
                    } else {
                        $data['stay_fee'] = 50;
                    }
                    break;
                case 'شاحنات وباصات خليجية':
                    $data['stay_fee'] = 50;
                    break;
            }

            $newEntry = new EntryStatement($data);

            $newEntry->created_at = $newDate;
            $newEntry->updated_at = $newDate;
            $newEntry->save();

            EntryCard::create([
                'entry_statement_id' => $newEntry->id,
                'owner_name' => $oldEntry->owner_name ?? 'اسم غير معروف',
                'car_number' => $oldEntry->car_number,
                'car_type' => $oldEntry->car_type,
                'stay_duration' => $request->add_week . ' شهر',
                'exit_date' => \Carbon\Carbon::parse($newDate)->addMonths($request->add_week)->toDateString(),
                'entry_date' => $newDate,
                'qr_code' => null,
            ]);

            EntryStatementLogHelper::log(
                $newEntry->id,
                'إنشاء تمديد',
                'تم إنشاء حركة جديدة بسبب تمديد المدة: ' . $request->add_week . ' أسابيع بتاريخ: ' . $newDate . ' - رقم تسلسلي: ' . $newEntry->serial_number
            );
            UserLogHelper::log('دفع رسوم', 'رقم الطلب: ' . $newEntry->serial_number);

            return redirect()->route('entry_statements.show', Crypt::encrypt($newEntry->id))->with('success', 'تم إنشاء حركة تمديد جديدة بنجاح.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error('فشل في إنشاء حركة دخول', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء الإضافة، يرجى المحاولة لاحقاً.');
        }
    }

    public function store(EntryStatementRequest $request)
    {
        try {
            $validated = $request->validated();

            $car_type = $validated['car_type'];
            $car_number = $validated['car_number'];

            switch ($car_type) {
                case 'سيارات غير السورية والاردنية واللبنانية':
                case 'دراجات نارية':
                    $validated['stay_fee'] = ($validated['stay_duration'] == 12) ? 200 : 50;
                    break;
                case 'شاحنات وباصات خليجية':
                    $validated['stay_fee'] = 50;
                    break;
                case 'سيارات سورية':
                    if ($validated['has_commitment']) {
                        $validated['stay_fee'] = 5;
                    } else {
                        $validated['stay_fee'] = 0;
                        $validated['completeFinanceEntry'] = 1;
                    }
                    $validated['stay_duration'] = 0;
                    break;
                case 'سيارات لبنانية':
                    $existing = EntryStatement::where('car_number', $car_number)->first();
                    if ($existing) {
                        if ($existing->book_type == 'خاص') {
                            if ($validated['has_commitment']) {
                                $validated['stay_fee'] = 15;
                            } else {
                                $validated['stay_fee'] = 10;
                            }
                            $validated['stay_duration'] = 0;
                        } elseif ($existing->book_type == 'عام') {
                            $validated['stay_fee'] = 0;
                            $validated['stay_duration'] = 0;
                            $validated['completeFinanceEntry'] = 1;
                        }
                    } else {
                        if ($validated['book_type'] == 'خاص') {
                            if ($validated['has_commitment']) {
                                $validated['stay_fee'] = 15;
                            } else {
                                $validated['stay_fee'] = 10;
                            }
                            $validated['stay_duration'] = 0;
                        } elseif ($validated['book_type'] == 'عام') {
                            $validated['stay_fee'] = 0;
                            $validated['stay_duration'] = 0;
                            $validated['completeFinanceEntry'] = 1;
                        }
                    }
                    break;
                case 'سيارات أردنية':
                    $existing = EntryStatement::where('car_number', $car_number)->first();
                    if ($existing) {
                        if ($existing->book_type == 'خاص') {
                            if ($validated['has_commitment']) {
                                $validated['stay_fee'] = 15;
                            } else {
                                $validated['stay_fee'] = 10;
                            }
                            $validated['stay_duration'] = 0;
                        } elseif ($existing->book_type == 'عام') {
                            $validated['stay_fee'] = 0;
                            $validated['stay_duration'] = 0;
                            $validated['completeFinanceEntry'] = 1;
                        }
                    } else {
                        if ($validated['book_type'] == 'خاص') {
                            if ($validated['has_commitment']) {
                                $validated['stay_fee'] = 15;
                            } else {
                                $validated['stay_fee'] = 10;
                            }
                            $validated['stay_duration'] = 0;
                        } elseif ($validated['book_type'] == 'عام') {
                            $validated['stay_fee'] = 0;
                            $validated['stay_duration'] = 0;
                            $validated['completeFinanceEntry'] = 1;
                        }
                    }
                    break;
            }

            if ($validated['car_brand'] == null) {
                $validated['car_brand'] = 'none';
            }

            $entry = EntryStatement::create($validated);

            EntryCard::create([
                'entry_statement_id' => $entry->id,
                'owner_name' => $validated['owner_name'] ?? 'اسم غير معروف',
                'car_number' => $validated['car_number'],
                'car_type' => $validated['car_type'],
                'stay_duration' => $validated['stay_duration'] . ' شهر',
                'entry_date' => now()->toDateString(),
                'exit_date' => now()->addMonths($validated['stay_duration'] ?? 1)->toDateString(),
                'qr_code' => null,
            ]);

            EntryStatementLogHelper::log($entry->id, 'إنشاء', 'رقم الطلب: #' . $entry->serial_number);
            UserLogHelper::log('انشاء حركة دخول', 'رقم الطلب: ' . $entry->serial_number);

            return redirect()->route('entry_statements.show', Crypt::encrypt($entry->id))->with('success', 'تمت الإضافة بنجاح.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error('فشل في إنشاء حركة دخول', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء الإضافة، يرجى المحاولة لاحقاً.');
        }
    }

    public function createByBook(Request $request)
    {
        return view('dashboard.entry_statements.book_create');
    }

    public function searchByBook(Request $request)
    {
        $book_number = $request->get('book_number');

        $foundEntry = EntryStatement::where('book_number', $book_number)->first();

        if (!$foundEntry) {
            return redirect()->back()->with('error', 'لم يتم العثور على بيانات بهذا الدفتر.');
        }

        return view('dashboard.entry_statements.book_create', [
            'foundEntry' => $foundEntry,
            'carTypes' => ['سيارات سورية', 'سيارات لبنانية', 'سيارات أردنية', 'شاحنات وباصات خليجية', 'دراجات نارية'],
            'borderCrossings' => BorderCrossing::all()
        ]);
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

        return redirect()->route('entry_statements.show', Crypt::encrypt($newEntry->id))->with('success', 'تم إنشاء الحركة بنجاح من رقم الدفتر.');
    }

    public function update(EntryStatementRequest $request, EntryStatement $entry_statement)
    {
        try {
            $validated = $request->validated();

            $car_type = $validated['car_type'];
            $car_number = $validated['car_number'];

            switch ($car_type) {
                case 'سيارات سورية او اردنية او لبنانية':
                    $existing = EntryStatement::where('car_number', $car_number)
                        ->where('id', '!=', $entry_statement->id)
                        ->first();
                    $validated['stay_fee'] = $existing ? 10 : 15;
                    $validated['stay_duration'] = 0;
                    break;
                case 'سيارات غير السورية والاردنية واللبنانية':
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
                case 'سيارات أردنية':
                    $existing = EntryStatement::where('car_number', $car_number)
                        ->where('id', '!=', $entry_statement->id)
                        ->first();
                    $validated['stay_fee'] = $existing ? 10 : 15;
                    $validated['stay_duration'] = 0;
                    break;
            }

            if ($validated['car_brand'] == null) {
                $validated['car_brand'] = 'none';
            }

            if (in_array($entry_statement->car_type, ['سيارات غير السورية والاردنية واللبنانية', 'دراجات نارية', 'شاحنات وباصات خليجية'])) {
                $validated['book_number'] = null;
                $validated['book_type'] = null;
            }
            $entry_statement->update($validated);

            EntryStatementLogHelper::log($entry_statement->id, 'تعديل', 'رقم الطلب: #' . $entry_statement->serial_number);
            UserLogHelper::log('تعديل حركة دخول', 'رقم الطلب: ' . $entry_statement->serial_number);

            return redirect()->route('dashboard')->with('success', 'تم التحديث بنجاح.');

        } catch (\Exception $e) {
            \Log::error('فشل في تحديث حركة دخول', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'entry_id' => $entry_statement->id
            ]);

            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء التحديث، يرجى المحاولة لاحقاً.');
        }
    }

    public function show($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $entry_statement = EntryStatement::with(['borderCrossing', 'exitBorderCrossing'])->findOrFail($id);

        // فقط المخالفات غير المدفوعة
        $unpaidViolations = $entry_statement->violations()->wherePivot('isCompleteFinance', false)->get();

        // تواريخ
        $createdAt = Carbon::parse($entry_statement->created_at);
        $stayDuration = $entry_statement->stay_duration; // الرقم

        $stayType = $entry_statement->stay_type ?? 'week';
        if ($stayDuration == 2) {
            $stayType = 'week';
        } else {
            $stayType = 'month';
        }
        // تحديد التاريخ المسموح
        if ($stayType === 'month') {
            $allowedStay = $createdAt->copy()->addMonths($stayDuration / 4);
        } else {
            $allowedStay = $createdAt->copy()->addDays($stayDuration * 7);
        }

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

        $additional_fees = $entry_statement->additionalFees;

        $additional_fees_total = $entry_statement->additionalFees()
            ->where('isCompleteFinance', false)
            ->sum('fee');

        $penalty = $penaltyPerWeek * $penaltyWeeks;

        if ($carType == 'شاحنات وباصات خليجية') {
            $exit_fee = 0;
        } else {
            if ($entry_statement->book_type == 'عام') {
                $exit_fee = 0;
            } else {
                $exit_fee = 5;
            }
        }

        // dd($exit_fee);

        $violations_total = $unpaidViolations->sum('fee');
        $total_exit_dollar = $exit_fee + $penalty + $violations_total + $additional_fees_total;

        $entry_fee = $entry_statement->stay_fee;
        $total_entry_dollar = $entry_statement->stay_fee + $violations_total + $additional_fees_total;

        $violations = Violation::all();
        $borderCrossings = BorderCrossing::all();

        // dd($entry_statement->type);

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
            'additional_fees',
            'additional_fees_total',
            'total_entry_dollar'
        ));
    }

    public function edit($encryptedId)
    {

        $id = Crypt::decrypt($encryptedId);
        $entryStatement = EntryStatement::findOrFail($id);
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

        return view('dashboard.entry_statements.edit', compact('entryStatement', 'carTypes', 'subCarTypes', 'borderCrossings'));
    }


    public function destroy(EntryStatement $entry_statement)
    {
        $entry_statement->delete();

        return redirect()->route('dashboard')->with('success', 'تم الحذف بنجاح.');
    }
}
