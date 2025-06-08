@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit car type</h2>
    <form action="{{ route('types.update', $type->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('dashboard.types.form', ['type' => $type])
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
