@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
        $createdAt = Carbon::parse($entry_statement->created_at);
        $weeks = $entry_statement->stay_duration;
        $allowedStay = $createdAt->copy()->addDays(($weeks * 7));
        $today = Carbon::now();

        $delayDays = $today->gt($allowedStay) ? $today->diffInDays($allowedStay) : 0;

        $penalty = 0;
        $penaltyPerWeek = 0;
        $penaltyWeeks = ceil($delayDays / 7);

        if ($delayDays > 0) {
            $carType = $entry_statement->car_type;

            if (in_array($carType, ['سيارات غير المذكورة', 'دراجات نارية'])) {
                $penaltyPerWeek = 110;
            } elseif ($carType == 'شاحنات وباصات خليجية') {
                $penaltyPerWeek = 50;
            }

            $penalty = $penaltyPerWeek * $penaltyWeeks;
        }
    @endphp
    @php
        $exit_fee = 5;
        $violations_total = $entry_statement->violations->sum('fee');
        $total_dollar = $exit_fee + $penalty + $violations_total; 
    @endphp
    <div class="container">
        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Finance'))
            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4 bg-light">

                    <h3 class="text-center text-primary fw-bolder mb-1" style="font-size: 1.8rem;">
                        💳 تفاصيل الفاتورة
                    </h3>
                    @if ($violations_total)
                        <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#ShowViolationsModal">
                            عرض المخالفات
                        </button>
                    @endif
                    <ul class="list-unstyled mb-1" style="font-size: 1.2rem;">
                        <li class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-dark fw-semibold">رسم الخروج:</span>
                            <span class="fw-bolder text-black fs-4">${{ number_format($exit_fee, 2) }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-dark fw-semibold">غرامة التأخير:</span>
                            <span class="fw-bolder fs-5 {{ $penalty > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $penalty > 0 ? number_format($penalty, 2) . ' دولار' : 'لا توجد غرامة' }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-dark fw-semibold">رسوم المخالفات:</span>
                            <span class="fw-bolder fs-5 {{ $violations_total > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $violations_total > 0 ? number_format($violations_total, 2) . ' دولار' : 'لا توجد مخالفات' }}
                            </span>
                        </li>
                    </ul>

                    <div class="border-top pt-1 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary fs-5">📦 <strong>المجموع الكلي:</strong></span>
                        <span class="fw-bolder text-success fs-3">{{ number_format($total_dollar, 2) }} دولار</span>
                    </div>
                </div>

                <div class="card-footer bg-white text-center">
                    <form action="{{ route('entry_statements.FinanceExit', $entry_statement->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="total_dollar" value="{{ $total_dollar }}">
                        <button type="submit" class="btn btn-success w-100 fw-bold py-1 rounded-pill">
                            ✅ تأكيد الدفع
                        </button>
                    </form>
                </div>
            </div>
        @endif
        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Customs'))
            <div class="card shadow-lg rounded">
                <div class="card-body text-end">
                    @if (!$entry_statement->is_checked_out)
                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Customs'))
                            @if ($entry_statement->completeFinanceExit)
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                    تسجيل الخروج لهذه السيارة
                                </button>
                            @else
                                <button class="btn btn-danger" disabled>
                                    لم يتم دفع الرسوم
                                </button>
                            @endif
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#timeModal">
                                تمديد مدة البقاء
                            </button>
                        @endif
                    @endif
                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Customs'))
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ViolationModal">
                            اضافة مخالفة
                        </button>
                    @endif
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ShowViolationsModal">
                        عرض المخالفات
                    </button>
                    <a href="{{ route('entry.logs', $entry_statement->id) }}" class="btn btn-info">
                        سجل التحركات
                    </a>
                </div>
            </div>
        @endif
        <div class="card shadow-lg rounded">
            <div class="card-header" style="background-color: #3c8dbc;">
                <h4 class="mb-0 text-white">تفاصيل حركة الدخول</h4>
                <a href="{{ route('entry_statements.index') }}" class="mb-0 btn btn-outline-light">
                    <i class="bi bi-arrow-left-circle"></i> رجوع
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="entryTable">
                    <tr>
                        <th class="bg-light">نوع السيارة</th>
                        <td>{{ $entry_statement->car_type }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">اسم السائق</th>
                        <td>{{ $entry_statement->driver_name }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">رقم السيارة</th>
                        <td>{{ $entry_statement->car_number }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">مدة البقاء</th>
                        <td>
                            @php
                                $weeks = $entry_statement->stay_duration;
                                $months = floor($weeks / 4);
                                $remainingWeeks = $weeks % 4;
                            @endphp

                            @if ($weeks >= 4)
                                {{ $months }} شهر{{ $months > 1 ? 'اً' : '' }}
                                @if ($remainingWeeks > 0)
                                    و{{ $remainingWeeks }} أسبوع{{ $remainingWeeks > 1 ? 'اً' : '' }}
                                @endif
                            @elseif($weeks == 0)
                                غير محدودة
                            @else
                                {{ $weeks }} أسبوع{{ $weeks > 1 ? 'اً' : '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light">الدخول</th>
                        <td>{{ $entry_statement->borderCrossing->name }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">رسم البقاء</th>
                        <td>{{ number_format($entry_statement->stay_fee, 2) }} ل.س</td>
                    </tr>
                    <tr>
                        <th class="bg-light">الرقم التسلسلي</th>
                        <td>{{ $entry_statement->serial_number }}</td>
                    </tr>
                    @if ($entry_statement->is_checked_out)
                        <tr>
                            <th class="bg-light">سجل خروج؟</th>
                            <td>{{ $entry_statement->is_checked_out == true ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">رسم الخروج</th>
                            <td>{{ number_format($entry_statement->exit_fee, 2) }} ل.س</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الخروج</th>
                            <td>{{$entry_statement->exitBorderCrossing->name }}</td>
                        </tr>
                    @endif
                    @if ($weeks > 0)
                        <tr>
                            <th class="bg-light">تاريخ الدخول</th>
                            <td>{{ $createdAt->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ الانتهاء المسموح</th>
                            <td>{{ $allowedStay->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">عدد أيام التأخير</th>
                            <td>
                                @if ($delayDays > 0)
                                    <span class="badge bg-danger">{{ $delayDays }} يوم متأخر</span>
                                @else
                                    <span class="badge bg-success">لا يوجد تأخير</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">عدد أسابيع التأخير</th>
                            <td>{{ $penaltyWeeks }} أسبوع</td>
                        </tr>
                        <tr>
                            <th class="bg-light">غرامة التأخير</th>
                            <td>
                                @if ($penalty > 0)
                                    {{ $penalty }} دولار
                                    <span class="text-muted" style="font-size: 13px;">
                                        ({{ $penaltyPerWeek }} دولار لكل أسبوع)
                                    </span>
                                @else
                                    لا توجد غرامة
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ الخروج</th>
                            <td>{{ $entry_statement->checked_out_date }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="font-weight: bold;">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">تأكيد عملية الخروج</h5>
                    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <h2>تم دفع رسوم الخروج</h2>
                </div>
                @if (auth()->user()->hasRole('Admin'))
                    <div class="col-md-6 mb-3" id="sub_car_type_wrapper">
                        <label class="form-label" for="border_crossing_id">الخروج من معبر:</label>
                        <select id="border_crossing_id" name="exit_border_crossing_id" class="form-control" required>
                            <option value="" disabled selected>اختر المعبر</option>
                            @foreach($borderCrossings as $crossing)
                                <option value="{{ $crossing->id }}" {{ old('border_crossing_id') == $crossing->id ? 'selected' : '' }}>
                                    {{ $crossing->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="modal-footer justify-content-between">
                    <form action="{{ route('entry_statements.checkout', $entry_statement->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="total_dollar" value="{{ $total_dollar }}">
                        <button type="submit" class="btn btn-success">تأكيد الخروج</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ViolationModal" tabindex="-1" aria-labelledby="ViolationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-weight: bold;">
                <div class="modal-header">
                    <h5 class="modal-title" id="ViolationModalLabel">اضافة مخالفة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('entry_statements.addviolation', $entry_statement->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="violation_id" class="form-label">اختر المخالفة</label>
                            <select name="violation_id" id="violation_id" class="form-select" required>
                                <option value="">-- اختر مخالفة --</option>
                                @foreach ($violations as $violation)
                                    <option value="{{ $violation->id }}">{{ $violation->title }} -
                                        {{ number_format($violation->fee, 2) }} $
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">تأكيد المخالفة</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'تم بنجاح',
                text: '{{ session('success') }}',
                confirmButtonText: 'حسناً'
            });
        </script>
    @endif
    <div class="modal fade" id="timeModal" tabindex="-1" aria-labelledby="timeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-weight: bold;">
                <div class="modal-header">
                    <h5 class="modal-title" id="timeModalLabel">تمديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('entry_statements.addviolation', $entry_statement->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="violation_id" class="form-label">اختر فترة التمديد</label>
                            <select name="violation_id" id="violation_id" class="form-select" required>
                                <option value="">اختر فترة التمديد</option>
                                <option value="4">شهر - 50$</option>
                                <option value="12">ثلاث أشهر 200$</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">تأكيد</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ShowViolationsModal" tabindex="-1" aria-labelledby="ShowViolationsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="font-weight: bold;">
                <div class="modal-header">
                    <h5 class="modal-title" id="ShowViolationsModalLabel">المخالفات المرتبطة بالحركة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    @if ($entry_statement->violations->isEmpty())
                        <p class="text-center text-danger">لا توجد مخالفات مرتبطة بهذه الحركة.</p>
                    @else
                        @php
        $createdAt = Carbon::parse($entry_statement->created_at);
        $weeks = $entry_statement->stay_duration;
        $allowedStay = $createdAt->copy()->addDays(($weeks * 7));
        $today = Carbon::now();

        $delayDays = $today->gt($allowedStay) ? $today->diffInDays($allowedStay) : 0;

        $penalty = 0;
        $penaltyPerWeek = 0;
        $penaltyWeeks = ceil($delayDays / 7);

        if ($delayDays > 0) {
            $carType = $entry_statement->car_type;

            if (in_array($carType, ['سيارات غير المذكورة', 'دراجات نارية'])) {
                $penaltyPerWeek = 110;
            } elseif ($carType == 'شاحنات وباصات خليجية') {
                $penaltyPerWeek = 50;
            }

            $penalty = $penaltyPerWeek * $penaltyWeeks;
        }
    @endphp
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان المخالفة</th>
                                    <th>قيمة الغرامة ($)</th>
                                    <th>تاريخ الإضافة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entry_statement->violations as $index => $violation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $violation->title }}</td>
                                        <td>{{ number_format($violation->fee, 2) }}</td>
                                        <td>{{ $violation->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    #entryTable {
        font-size: 15px;
        font-weight: bold;
    }
</style>