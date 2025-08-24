@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-1 text-center">سجل العمليات المالية</h2>

        {{-- فلترة --}}
        @if ($box)
            <form method="GET" action="{{ route('finance.box.transactions', $box->id) }}" class="mb-1">
                <div class="row g-2 justify-content-center">
                    <div class="col-md-3">
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control"
                            placeholder="من تاريخ">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control"
                            placeholder="إلى تاريخ">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">فلترة</button>
                        <a href="{{ route('finance.box.transactions', $box->id) }}" class="btn btn-secondary w-100">إلغاء</a>
                    </div>
                </div>
            </form>
        @endif

        {{-- الكروت --}}
        <div class="row text-center mb-1">
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm">
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

        <div class="col-md-3 mb-1">
            <input type="text" name="serial" id="serialSearch" value="{{ request('serial') }}" class="form-control"
                placeholder="بحث بالرقم التسلسلي">
        </div>

        {{-- الجدول --}}
        @if ($transactions->isEmpty())
            <p class="text-center text-muted fs-5">لا توجد عمليات في هذا اليوم.</p>
        @else
            <div class="table-responsive shadow-sm">
                <table class="table table-sm table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>الوصف</th>
                            <th>العملية لأجل</th>
                            <th>المبلغ</th>
                            <th>التاريخ والوقت</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $t)
                            <tr>
                                <td>{{ $t->description }}</td>
                                <td>{{ $t->operation_for }}</td>
                                <td class="text-success fw-semibold">{{ number_format($t->amount, 2) }} $</td>
                                <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#detailModal{{ $t->id }}"
                                        class="btn btn-sm btn-info">
                                        تفاصيل
                                    </button>
                                    <a href="{{ route('print.card', $t->id) }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="bi bi-printer"></i> طباعة
                                    </a>
                                </td>
                            </tr>

                            {{-- مودال تفاصيل --}}
                            <div class="modal fade" id="detailModal{{ $t->id }}">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">تفاصيل الدفعة</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @php $d = $t->detail; @endphp
                                            <p><strong>الرسوم:</strong> {{ number_format($d->fee, 2) }} $</p>
                                            <p><strong>الغرامات:</strong> {{ number_format($d->penalty, 2) }} $</p>
                                            <p><strong>المخالفات:</strong> {{ number_format($d->violations_total, 2) }} $</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script>
        document.getElementById('serialSearch').addEventListener('input', function () {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("table tbody tr");

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? "" : "none";
            });
        });
    </script>
@endsection