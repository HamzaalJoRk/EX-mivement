@extends('layouts.app')

@section('content')
    <div class="container">
        @if (auth()->user()->hasRole('admin'))
            <h1>إنشاء حركة دخول</h1>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>حدثت الأخطاء التالية:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('entry_statements.store') }}" method="POST">
            @csrf

            @include('dashboard.entry_statements.form', ['entry_statement' => null])

            <button type="submit" class="btn btn-primary mt-1">إنشاء</button>
        </form>
    </div>
@endsection