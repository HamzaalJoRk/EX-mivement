@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Violation</h1>

    <form action="{{ route('violations.update', $violation) }}" method="POST">
        @csrf
        @method('PUT')
        @include('violations.form')
        <button type="submit" class="btn btn-success mt-3">Update</button>
    </form>
</div>
@endsection
