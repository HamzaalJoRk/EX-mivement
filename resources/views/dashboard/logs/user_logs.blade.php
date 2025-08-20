@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-1">سجل عمليات المستخدم: {{ $user->name }}</h4>

        <a href="{{ route('users.index') }}" class="btn btn-secondary mb-1">الرجوع لقائمة المستخدمين</a>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>العملية</th>
                    <th>تفاصيل</th>
                    <th>الوقت</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->details }}</td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">لا توجد سجلات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $logs->links() }}
    </div>
@endsection