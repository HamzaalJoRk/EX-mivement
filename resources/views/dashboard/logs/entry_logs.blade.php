@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>سجل التحركات للعملية رقم: {{ $serial_number }}</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المستخدم</th>
                    <th>العملية</th>
                    <th>التفاصيل</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->user->name ?? 'غير معروف' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->details }}</td>
                        <td>{{ $log->created_at->format('Y-m-d h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">لا توجد تحركات مسجلة بعد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection