@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Late Fee</h1>

    <form action="{{ route('late-fees.update', $lateFee) }}" method="POST">
        @csrf
        @method('PUT')
        @include('late_fees.form')
        <button type="submit" class="btn btn-success mt-3">Update</button>
    </form>
</div>
@endsection
