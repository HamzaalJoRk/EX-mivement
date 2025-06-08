@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>حركات الدخول والخروج</h1>

        @if (!auth()->user()->hasRole('admin'))
            <a href="{{ route('entry-statements.create') }}" class="btn btn-primary mb-3">اضافة حركة دخول</a>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered" id="entriesTable">
            <thead>
                <tr>
                    <th>اسم المالك</th>
                    <th>رقم السيارة</th>
                    <th>نوع السيارة</th>
                    <th>مدة البقاء</th>
                    <th>الرسوم</th>
                    <th>تاريخ الدخول</th>
                    <th>حالة الخروج</th>
                    <th>الاجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                    @php
                        $isExited = $entry->exitStatement !== null;
                    @endphp
                    <tr>
                        <td>{{ $entry->owner_name }}</td>
                        <td>{{ $entry->car_number }}</td>
                        <td>{{ $entry->car_type == 1 ? "سيارة سياحية" : "باص" }}</td>
                        <td>{{ $entry->entry_period == 1 ? "شهر" : "ثلاث أشهر" }}</td>
                        <td>{{ $entry->entranceFee->fees ?? '--' }} $</td>
                        <td>{{ $entry->date }}</td>
                        <td>
                            @if($isExited)
                                <span class="text-success">تمت المغادرة بتاريخ: {{ $entry->exitStatement->date }}</span>
                            @else
                                <span class="text-danger">لم يغادر بعد</span><br>
                                <button class="btn btn-primary open-exit-modal" data-id="{{ $entry->id }}"
                                    data-owner-name="{{ $entry->owner_name }}" data-car-number="{{ $entry->car_number }}"
                                    data-car-type="{{ $entry->car_type }}" data-entry-period="{{ $entry->entry_period }}"
                                    data-entry-date="{{ $entry->created_at }}">
                                    إضافة حركة خروج
                                </button>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('entry-statements.show', $entry) }}" class="btn btn-info btn-sm">عرض</a>
                            <a href="{{ route('entry-statements.edit', $entry) }}" class="btn btn-warning btn-sm">تعديل</a>
                            <form action="{{ route('entry-statements.destroy', $entry) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('هل أنت متأكد؟')" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">تسجيل حركة خروج</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="exit-form" method="POST" action="{{ route('exit-statements.store') }}">
                    @csrf
                    <input type="hidden" name="entry_statement_id" id="entry_statement_id">
                    <input type="hidden" name="car_number" id="car_number">
                    <input type="hidden" name="owner_name" id="owner_name">
                    <input type="hidden" name="date" id="exit_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

                    <div class="modal-body">
                        <p>تاريخ الدخول: <span id="entry-date"></span></p>
                        <p>تاريخ الخروج: <span id="exit-date"></span></p>
                        <p>المدة المحددة: <span id="allowed-days"></span> يوم</p>
                        <p>عدد أيام التأخير: <span id="late-days"></span></p>
                        <p>عدد أسابيع التأخير: <span id="late-weeks"></span></p>
                        <p>قيمة الغرامة: <strong><span id="penalty-amount"></span> $</strong></p>

                        <div class="mb-2">
                            <label>رسم الخروج</label>
                            <input type="number" step="0.01" name="fee_value" id="fee_value" class="form-control"
                                value="5.00">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">تأكيد الخروج</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // تفعيل DataTables
        $('#entriesTable').DataTable({
            "responsive": true, // يجعل الجدول قابلاً للتوسيع والتضييق
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/ar.json" // تحديد اللغة العربية
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.open-exit-modal');
        const modal = new bootstrap.Modal(document.getElementById('exitModal'));

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const carType = this.dataset.carType;
                const periodMonths = parseInt(this.dataset.entryPeriod);
                const entryDateStr = this.dataset.entryDate;
                const carNumber = this.dataset.carNumber;
                const ownerName = this.dataset.ownerName;

                const entryDate = new Date(entryDateStr);
                const now = new Date();

                const allowedDate = new Date(entryDate);
                allowedDate.setMonth(allowedDate.getMonth() + periodMonths);

                const lateMilliseconds = now - allowedDate;
                const lateDays = Math.ceil(lateMilliseconds / (1000 * 60 * 60 * 24));
                const lateWeeks = lateDays > 0 ? Math.ceil(lateDays / 7) : 0;
                const penalty = carType == 1 ? lateWeeks * 110 : lateWeeks * 20;

                document.getElementById('entry_statement_id').value = id;
                document.getElementById('car_number').value = carNumber;
                document.getElementById('owner_name').value = ownerName;

                document.getElementById('entry-date').textContent = entryDate.toLocaleDateString();
                document.getElementById('exit-date').textContent = now.toLocaleDateString();
                document.getElementById('allowed-days').textContent = periodMonths * 30;
                document.getElementById('late-days').textContent = lateDays > 0 ? lateDays : 0;
                document.getElementById('late-weeks').textContent = lateWeeks;
                document.getElementById('penalty-amount').textContent = penalty.toFixed(2);
                document.getElementById('fee_value').value = "5.00";

                modal.show();
            });
        });
    });
</script>