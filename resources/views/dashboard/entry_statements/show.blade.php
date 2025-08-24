@extends('layouts.app')

@section('content')
@php
    use Picqer\Barcode\BarcodeGeneratorSVG;

    $generator = new BarcodeGeneratorSVG();
    $barcode = $generator->getBarcode($entry_statement->serial_number, $generator::TYPE_CODE_128);
@endphp

    <div class="container">
        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Finance'))
            @if ($entry_statement->completeFinanceEntry && !$entry_statement->completeFinanceExit)
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-4 bg-light">
                        <h3 class="text-center text-primary fw-bolder mb-1" style="font-size: 1.8rem;">
                            رسوم الخروج
                        </h3>
                        @if ($violations_total)
                            <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal"
                                data-bs-target="#ShowViolationsModal">
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
                            <span class="fw-bolder text-success fs-3">{{ number_format($total_exit_dollar, 2) }} دولار</span>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-center">
                        <form action="{{ route('entry_statements.FinanceExit', $entry_statement->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="exit_fee" value="{{ $exit_fee }}">
                            <input type="hidden" name="penalty" value="{{ $penalty }}">
                            <input type="hidden" name="violations_total" value="{{ $violations_total }}">
                            <input type="hidden" name="additional_fees_total" value="{{ $additional_fees_total }}">
                            <input type="hidden" name="total_exit_dollar" value="{{ $total_exit_dollar }}">
                            @if (auth()->user()->hasRole('Finance'))
                                @if ($entry_statement->completeFinanceEntry && !$entry_statement->is_checked_in)
                                <p class="text-danger">لم تقم بالدخول من البوابة السورية لذا لا يمكن تأكيد الدفع</p>
                                    <button type="submit" class="btn btn-success w-100 fw-bold py-1 rounded-pill mt-1" disabled target="_blank">
                                        ✅ تأكيد الدفع
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success w-100 fw-bold py-1 rounded-pill" target="_blank">
                                        ✅ تأكيد الدفع
                                    </button>
                                @endif
                            @else
                                <button type="submit" class="btn btn-success w-100 fw-bold py-1 rounded-pill" target="_blank" disabled>
                                    ✅ تأكيد الدفع
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @elseif (!$entry_statement->completeFinanceEntry)
                <div class="card shadow rounded-4 border-0">
                    <div class="card-body p-4 bg-light">
                        <h3 class="text-center text-primary fw-bolder mb-1" style="font-size: 1.8rem;">
                            رسوم الدخول
                        </h3>
                        @if ($violations_total)
                            <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal"
                                data-bs-target="#ShowViolationsModal">
                                عرض المخالفات
                            </button>
                        @endif
                        <li class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-dark fw-semibold">رسم اضافي:</span>
                            <span class="fw-bolder text-black fs-4">${{ number_format($additional_fees_total, 2) }}</span>
                        </li>
                        <ul class="list-unstyled mb-1" style="font-size: 1.2rem;">
                            <li class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-dark fw-semibold">رسم الدخول:</span>
                                <span class="fw-bolder text-black fs-4">${{ number_format($entry_fee, 2) }}</span>
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
                            <span class="fw-bolder text-success fs-3">{{ number_format($total_entry_dollar, 2) }} دولار</span>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-center">
                        <form action="{{ route('entry_statements.FinanceEntry', $entry_statement->id) }}" target="_blank" method="POST">
                            @csrf
                            <input type="hidden" name="entry_fee" value="{{ $entry_fee }}">
                            <input type="hidden" name="violations_total" value="{{ $violations_total }}">
                            <input type="hidden" name="total_entry_dollar" value="{{ $total_entry_dollar }}">
                            <input type="hidden" name="additional_fees_total" value="{{ $additional_fees_total }}">
                            @if (auth()->user()->hasRole('Finance'))
                                <button type="submit" class="btn btn-success w-100 fw-bold py-1 rounded-pill" target="_blank">
                                    ✅ تأكيد الدفع
                                </button>
                            @else
                                <button type="submit" class="btn btn-success w-100 fw-bold py-1 rounded-pill" target="_blank" disabled>
                                    ✅ تأكيد الدفع
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        @endif
        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('CustomEntry') || auth()->user()->hasRole('CustomExit'))
            <div class="card shadow-lg rounded">
                <div class="card-body text-end">
                    @if (!$entry_statement->is_checked_out || !$entry_statement->completeFinanceEntry)
                        @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('CustomEntry') || auth()->user()->hasRole('CustomExit'))
                            @if ($entry_statement->completeFinanceEntry == true)
                                @if($entry_statement->completeFinanceExit)
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                        تسجيل الخروج لهذه السيارة
                                    </button>
                                @else
                                    <button class="btn btn-danger" disabled>
                                        لم يتم دفع الرسوم
                                    </button>
                                @endif
                            @endif
                        @endif
                    @endif
                    @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('CustomEntry'))
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ViolationModal">
                            اضافة مخالفة
                        </button>
                    @endif
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ShowViolationsModal">
                        عرض المخالفات
                    </button>
                    @if ($entry_statement->is_checked_in == 0)
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeeModal">
                            إضافة ترسيم
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#showFeeModal">
                            عرض الترسيمات
                        </button>
                    @endif
                    <a href="{{ route('entry.logs', $entry_statement->id) }}" class="btn btn-info">
                        سجل التحركات
                    </a>
                </div>
            </div>
        @endif
        <div class="card shadow-lg rounded">
            <div class="card-header" style="background-color: #3c8dbc;">
                <h4 class="mb-0 text-white">تفاصيل حركة الدخول</h4>
                <div>
                    <a href="{{ route('entry-cards.print', $entry_statement->entryCard->id) }}" target="_blank" class="btn btn-success">
                        <i class="bi bi-printer"></i> طباعة البطاقة
                    </a>
                    @if ($entry_statement->completeFinanceExit == true)
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#timeModal">
                            تمديد مدة البقاء
                        </button>
                    @endif
                    @if (!auth()->user()->hasRole('Finance'))
                    <a href="{{ route('entry_statements.create') }}" class="mb-0 btn btn-outline-light">
                        <i class="bi bi-arrow-left-circle"></i> تسجيل حركة جديدة
                    </a>
                    @endif
                    @if (auth()->user()->hasRole('Admin'))
                        <a href="{{ route('entry_statements.index') }}" class="mb-0 btn btn-outline-light">
                            <i class="bi bi-arrow-left-circle"></i> رجوع
                        </a>
                    @else
                        <a href="{{ route('entrySearch') }}" class="mb-0 btn btn-outline-light">
                            <i class="bi bi-arrow-left-circle"></i> رجوع
                        </a>
                    @endif
                </div>
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
                    @if (in_array($entry_statement->car_type, ['سيارات سورية', 'سيارات لبنانية', 'سيارات أردنية']))    
                        
                        <tr>
                            <th class="bg-light">رقم الدفتر</th>
                            <td>{{ $entry_statement->book_number }}</td>
                        </tr>
                        
                        <tr>
                            <th class="bg-light">نوع الدفتر</th>
                            <td>{{ $entry_statement->book_type }}</td>
                        </tr>
                    @endif
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
                        <th class="bg-light">رسم الدخول</th>
                        <td>
                            {{ number_format($entry_statement->stay_fee, 2) }} $ 
                            @if ($entry_statement->completeFinanceEntry)
                                <span style="color: green; font-size: smaller;">تم الدفع</span>
                            @else
                                <span style="color: red; font-size: smaller;">لم يتم الدفع</span>
                            @endif
                        </td>
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
                            <td>{{ number_format($exit_fee, 2) }} $</td>
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

    <div class="modal fade" id="addFeeModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('additional_fees.store', $entry_statement->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">إضافة ترسيم إضافي</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>عنوان الرسوم</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>المبلغ</label>
                        <input type="number" name="fee" step="0.01" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">إضافة</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="showFeeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="font-weight: bold;">
                <div class="modal-header">
                    <h5 class="modal-title" id="ShowViolationsModalLabel">الرسوم الإضافية (الترسيم)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    @if ($entry_statement->additionalFees->isEmpty())
                        <p class="text-center text-danger">لا توجد رسوم اضافية مرتبطة بهذه الحركة.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>القيمة</th>
                                    <th>تم التسديد؟</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($additional_fees as $index =>  $fee)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $fee->title }}</td>
                                        <td>{{ number_format($fee->fee, 2) }} $</td>
                                        @if ($fee->isCompleteFinance)
                                            <td>
                                                <span style="color: green;">تم الدفع</span>
                                            </td>
                                        @else
                                            <td>
                                                <span style="color: red;">لم يتم الدفع</span>
                                            </td>
                                        @endif
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

                <div class="modal-footer justify-content-between">
                    <form action="{{ route('entry_statements.checkout', $entry_statement->id) }}" method="POST">
                        @csrf
                        @if (auth()->user()->hasRole('Admin'))
                            <div class="col-md-12 mb-3" id="sub_car_type_wrapper">
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
                        <input type="hidden" name="total_dollar" value="{{ $total_exit_dollar }}">
                        <button type="submit" class="btn btn-success">تأكيد الخروج</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </form>
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
    <div class="modal fade" id="timeModal" tabindex="-1" aria-labelledby="timeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-weight: bold;">
                <div class="modal-header">
                    <h5 class="modal-title" id="timeModalLabel">تمديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('entry_statements.addTime', $entry_statement->id) }}" method="POST">
                    @csrf
                    @if ($penalty > 0)
                        <input type="hidden" name="penalty" value="{{ $penalty }}">
                    @endif
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="violation_id" class="form-label">اختر فترة التمديد</label>
                            <select name="add_week" id="violation_id" class="form-select" required>
                                <option value="">اختر فترة التمديد</option>
                                @if ($entry_statement->car_type == 'سيارات غير السورية والاردنية واللبنانية')
                                    <option value="4">شهر - 50$</option>
                                    <option value="12">ثلاث أشهر 200$</option>
                                @elseif($entry_statement->car_type == 'شاحنات وباصات خليجية')
                                    <option value="2">اسبوعين 50$</option>
                                @endif
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان المخالفة</th>
                                    <th>قيمة الغرامة ($)</th>
                                    <th>تاريخ الإضافة</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entry_statement->violations as $index => $violation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $violation->title }}</td>
                                        <td>{{ number_format($violation->fee, 2) }}</td>
                                        <td>{{ $violation->created_at->format('Y-m-d H:i') }}</td>
                                        @if ($violation->pivot->isCompleteFinance)
                                            <td>
                                                <span style="color: green;">تم الدفع</span>
                                            </td>
                                        @else
                                            <td>
                                                <span style="color: red;">لم يتم الدفع</span>
                                            </td>
                                        @endif
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
    <div id="printCard" class="d-none">
    <div style="width: 350px; padding: 20px; border: 1px solid #000; font-family: Arial, sans-serif;">
        <h3 class="text-center text-primary">بطاقة الخروج</h3>
        <p><strong>اسم السائق:</strong> {{ $entry_statement->driver_name }}</p>
        <p><strong>رقم السيارة:</strong> {{ $entry_statement->car_number }}</p>
        <p><strong>نوع السيارة:</strong> {{ $entry_statement->car_type }}</p>
        <p><strong>مدة البقاء:</strong> 
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
        </p>
        <p><strong>المجموع الكلي:</strong> {{ number_format($total_exit_dollar ?? $total_entry_dollar, 2) }} دولار</p>
        <p class="text-center mt-3">✅ تم الدفع</p>
        <p><strong>الرقم التسلسلي:</strong> {{ $entry_statement->serial_number }}</p>
<div class="text-center mt-2">
    {!! QrCode::size(100)->generate($entry_statement->serial_number) !!}
</div>
    </div>
</div>
<script>
    function printCard() {
        var printContents = document.getElementById('printCard').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
@endsection
<style>
    #entryTable {
        font-size: 15px;
        font-weight: bold;
    }
</style>
