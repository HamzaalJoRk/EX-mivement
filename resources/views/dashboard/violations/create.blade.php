@extends('layouts.app')

@section('content')
<div class="container">
    <h1>انشاء مخالفة</h1>

    <form action="{{ route('violations.store') }}" method="POST">
        @csrf
        @include('dashboard.violations.form')
        <button type="submit" class="btn btn-success mt-1">انشاء</button>
    </form>
</div>
@endsection
