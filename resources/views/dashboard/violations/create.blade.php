@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Violation</h1>

    <form action="{{ route('violations.store') }}" method="POST">
        @csrf
        @include('dashboard.violations.form')
        <button type="submit" class="btn btn-success mt-3">Create</button>
    </form>
</div>
@endsection
