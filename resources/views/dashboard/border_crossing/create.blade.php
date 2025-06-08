@extends('layouts.app')

@section('content')
<div class="container">
    <h2>اضافة معبر</h2>
    <form action="{{ route('border_crossing.store') }}" method="POST">
        @csrf
        @include('dashboard.border_crossing.form')
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
