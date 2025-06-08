@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تعديل اسم المعبر</h2>
    <form action="{{ route('border_crossing.update', $borderCrossing->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('dashboard.border_crossing.form', ['borderCrossing' => $borderCrossing])
        <button type="submit" class="btn btn-success">تعديل</button>
    </form>
</div>
@endsection
