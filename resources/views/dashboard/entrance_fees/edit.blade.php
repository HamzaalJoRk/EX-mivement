@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Entrance Fee</h2>
    <form action="{{ route('entrance-fees.update', $entranceFee->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('dashboard.entrance_fees.form', ['entranceFee' => $entranceFee])
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
