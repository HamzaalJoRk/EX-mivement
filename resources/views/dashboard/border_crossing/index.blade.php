@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>جميع المعابر</h2>
        <a href="{{ route('border_crossing.create') }}" class="btn btn-primary mb-3">اضافة معبر</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المعبر</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borderCrossings as $borderCrossing)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $borderCrossing->name }}</td>
                        <td>
                            <a href="{{ route('border_crossing.edit', $borderCrossing->id) }}"
                                class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('border_crossing.destroy', $borderCrossing->id) }}" method="POST"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Confirm delete?')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection