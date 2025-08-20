@extends('layouts.app')

@section('content')
<div class="container">
    <h1>المخالفات</h1>
    <a href="{{ route('violations.create') }}" class="btn btn-primary mb-3">اضافة محالفات</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>المبلغ</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($violations as $violation)
                <tr>
                    <td>{{ $violation->id }}</td>
                    <td>{{ $violation->title }}</td>
                    <td>{{ $violation->fee }}</td>
                    <td>
                        <a href="{{ route('violations.edit', $violation) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('violations.destroy', $violation) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
