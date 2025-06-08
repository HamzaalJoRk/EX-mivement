@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Entrance Fee</h2>
    <form action="{{ route('entrance-fees.store') }}" method="POST">
        @csrf
        @include('dashboard.entrance_fees.form')
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
