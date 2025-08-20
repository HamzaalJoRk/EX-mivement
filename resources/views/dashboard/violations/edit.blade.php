@extends('layouts.app')

@section('content')
<div class="container">
    <h1>تعديل المخالفة</h1>

    <form action="{{ route('violations.update', $violation) }}" method="POST">
        @csrf
        @method('PUT')
        @include('dashboard.violations.form')
        <button type="submit" class="btn btn-success mt-3">تعديل</button>
    </form>
</div>
@endsection
