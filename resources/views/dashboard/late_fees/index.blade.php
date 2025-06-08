@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Late Fees</h1>
    <a href="{{ route('late-fees.create') }}" class="btn btn-primary mb-3">Add Late Fee</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Entry Statement ID</th>
                <th>Type</th>
                <th>Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lateFees as $fee)
                <tr>
                    <td>{{ $fee->entry_statement_id }}</td>
                    <td>{{ $fee->type }}</td>
                    <td>{{ $fee->fee }}</td>
                    <td>
                        <a href="{{ route('late-fees.edit', $fee) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('late-fees.destroy', $fee) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
