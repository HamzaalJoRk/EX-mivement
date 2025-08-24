@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-1 text-center">📊 عرض الصناديق والمبالغ</h2>

    <!-- فلترة -->
    <div class="card shadow-sm mb-1">
        <div class="card-body">
            <form method="GET" action="{{ route('finance.boxes.index') }}">
                <div class="row justify-content-center align-items-end g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">تاريخ البداية:</label>
                        <input type="date" id="startDate" name="startDate" value="{{ $startDate }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">تاريخ النهاية:</label>
                        <input type="date" id="endDate" name="endDate" value="{{ $endDate }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> فلترة
                        </button>
                        <a href="{{ route('finance.boxes.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- مجموع المبالغ -->
    @php
        $total = $boxes->sum('total_amount');
    @endphp
    <div class="card text-white bg-success mb-1 shadow-sm" style="max-width: 400px; margin: auto;">
        <div class="card-body text-center">
            <h5 class="card-title">إجمالي المبلغ المستلم</h5>
            <p class="card-text display-6 fw-bold">{{ number_format($total, 2) }} $</p>
        </div>
    </div>

    <!-- جدول الصناديق -->
    <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>اسم الصندوق</th>
                    <th>المستخدم المسؤول</th>
                    <th>عدد العمليات</th>
                    <th class="text-success">إجمالي المبلغ</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($boxes as $index => $box)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $box->name }}</td>
                        <td>{{ $box->user->name ?? 'غير معروف' }}</td>
                        <td>{{ $box->transactions_count }}</td>
                        <td class="fw-bold text-success">{{ number_format($box->total_amount, 2) }} $</td>
                        <td>
                            <a href="{{ route('finance.box.transactions', $box->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> عرض العمليات
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">لا توجد صناديق لعرضها.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
