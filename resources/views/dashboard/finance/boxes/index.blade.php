@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">عرض الصناديق والمبالغ</h2>

        <form method="GET" action="{{ route('finance.boxes.index') }}" class="mb-4">
            <div class="row justify-content-center align-items-end g-2">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">تاريخ البداية:</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">تاريخ النهاية:</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">فلترة</button>
                    <a href="{{ route('finance.boxes.index') }}" class="btn btn-secondary w-100">إلغاء الفلترة</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>اسم الصندوق</th>
                        <th>المستخدم المسؤول</th>
                        <th>عدد العمليات خلال الفترة</th>
                        <th>إجمالي المبلغ</th>
                        <th>اجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($boxes as $index => $box)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $box->name }}</td>
                            <td>{{ $box->user->name ?? 'غير معروف' }}</td>
                            <td>{{ $box->filtered_transactions->count() }}</td>
                            <td><strong>{{ number_format($box->total_amount, 2) }}</strong></td>
                            <td><a href="{{ route('finance.box.transactions', $box->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> عرض العمليات
                                </a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">لا توجد صناديق لعرضها.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection