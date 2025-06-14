@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-2 text-center">سجل العمليات المالية</h2>

        <form method="GET" action="{{ route('finance.transactions.index') }}" class="mb-4">
            <div class="row justify-content-center align-items-end g-2">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">تاريخ البداية:</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}"
                        class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">تاريخ النهاية:</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">فلترة</button>
                    <a href="{{ route('finance.transactions.index') }}" class="btn btn-secondary w-100">إلغاء الفلترة</a>
                </div>
            </div>
        </form>



        <div class="card text-white bg-success mb-4" style="max-width: 400px; margin: auto;">
            <div class="card-body text-center">
                <h5 class="card-title">مجموع المبلغ المستلم</h5>
                <p class="card-text display-6 fw-bold">{{ number_format($total, 2) }} ل.س</p>
            </div>
        </div>

        @if ($transactions->isEmpty())
            <p class="text-center text-muted fs-5">لا توجد عمليات في هذا اليوم.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>الوصف</th>
                            <th>العملية لأجل</th>
                            <th>المبلغ</th>
                            <th>التاريخ والوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $t)
                            <tr>
                                <td>{{ $t->description }}</td>
                                <td>{{ $t->operation_for }}</td>
                                <td class="text-success fw-semibold">{{ number_format($t->amount, 2) }} ل.س</td>
                                <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection