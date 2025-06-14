@extends('layouts.app')

@section('content')
    <div class="container">

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (auth()->user()->hasRole('CustomEntry'))
            <form method="GET" action="{{ route('CompleteEnrty') }}" class="mb-3">
                <label class="mb-1">تسجيل الدخول للحركة: </label>
                <div class="input-group">
                    <input type="text" name="serial_number" class="form-control" placeholder="ابحث بالرقم التسلسلي"
                        value="{{ request('serial_number') }}" required>
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </form>
        @elseif (auth()->user()->hasRole('CustomExit'))
            <form method="GET" action="{{ route('CompleteExit') }}" class="mb-3">
                <label class="mb-1">تسجيل الخروج للحركة: </label>
                <div class="input-group">
                    <input type="text" name="serial_number" class="form-control" placeholder="ابحث بالرقم التسلسلي"
                        value="{{ request('serial_number') }}" required>
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </form>
        @else
        <form method="GET" action="{{ route('entrySearch.show') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="serial_number" class="form-control" placeholder="ابحث بالرقم التسلسلي"
                    value="{{ request('serial_number') }}" required>
                <button type="submit" class="btn btn-primary">بحث</button>
            </div>
        </form>
        @endif
    </div>
@endsection