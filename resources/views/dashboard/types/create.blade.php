@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Car Type</h2>
    <form action="{{ route('types.store') }}" method="POST">
        @csrf
        @include('dashboard.types.form')
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection
