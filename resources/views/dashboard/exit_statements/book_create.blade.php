@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-1">إنشاء حركة خروج عن طريق رقم الدفتر</h1>

        <form action="{{ route('exit_statements.searchBook') }}" method="GET" class="mb-1">
            <div class="row">
                <div class="col-md-6">
                    <label class="mb-1">ابحث برقم الدفتر</label>
                    <input type="text" name="book_number" class="form-control" placeholder="أدخل رقم الدفتر" required>
                </div>
            </div>
            <div class="col-md-2 mt-1">
                <button type="submit" class="btn btn-primary">بحث</button>
            </div>
        </form>
        @if (session('error'))
            <div class="alert alert-danger p-2">
                لم يتم العثور على دفتر المسافر
            </div>
        @endif
        @if(isset($foundEntry))
            <div class="alert alert-info">
                تم العثور على الدفتر رقم <strong>{{ $foundEntry->book_number }}</strong>
                ({{ $foundEntry->driver_name }} - {{ $foundEntry->car_number }})
            </div>

            <form action="{{ route('exit_statements.storeFromBook') }}" method="POST">
                @csrf
                <input type="hidden" name="book_number" value="{{ $foundEntry->book_number }}">
                <button type="submit" class="btn btn-primary">إنشاء حركة جديدة</button>
            </form>
        @endif
    </div>
@endsection