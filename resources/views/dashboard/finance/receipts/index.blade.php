@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-1 text-center">سجل العمليات المالية</h2>

        {{-- فلترة --}}
        <form method="GET" action="{{ route('finance.receipts.index') }}" class="mb-3">
            <div class="row g-2 justify-content-center">
                <div class="col-md-3">
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}" class="form-control"
                        placeholder="من تاريخ">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" class="form-control"
                        placeholder="إلى تاريخ">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">فلترة</button>
                    <a href="{{ route('finance.receipts.index') }}" class="btn btn-secondary w-100">إلغاء</a>
                </div>
            </div>
        </form>

        {{-- الكروت --}}
        <div class="row text-center mb-1">
            <div class="col-md-4">
                <div class="card bg-success shadow-sm">
                    <div class="card-body">
                        <h6 class="text-white mb-1">المبلغ المستلم</h6>
                        <h4 class="text-white fw-bold">{{ number_format($total, 2) }} $</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-1 text-success">مجموع الرسوم</h6>
                        <h5 class="fw-bold">{{ number_format($totalFees, 2) }} $</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-warning shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-1 text-warning">مجموع الغرامات</h6>
                        <h5 class="fw-bold">{{ number_format($totalPenalties + $totalViolations, 2) }} $</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" id="serialSearch" class="form-control" placeholder="بحث بالرقم/السائق/الصندوق...">
        </div>
        {{-- الجدول --}}
        @if ($transactions->isEmpty())
            <p class="text-center text-muted fs-5">لا توجد عمليات في هذا اليوم.</p>
        @else
            <div class="table-responsive shadow-sm">
                <table class="table table-sm table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>الرقم</th>
                            <th>رقم التصفية</th>
                            <th>اسم السائق</th>
                            <th>الصندوق</th>
                            <th>الإجمالي</th>
                            <th>التاريخ والوقت</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $t)
                            <tr>
                                <td>{{ $t->receipt_number }}</td>
                                <td>{{ $t->statement_number }}</td>
                                <td>{{ $t->driver_name }}</td>
                                <td>{{ $t->cashier_name }}</td>
                                <td class="text-success fw-semibold">{{ number_format($t->amount, 2) }} $</td>
                                <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('print.card', $t->id) }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="bi bi-printer"></i> طباعة
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- فلترة مباشرة --}}
    <script>
        document.getElementById('serialSearch').addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("table tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? "" : "none";
            });
        });
    </script>
@endsection