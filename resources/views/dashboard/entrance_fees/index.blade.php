@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Entrance Fees</h2>
    <a href="{{ route('entrance-fees.create') }}" class="btn btn-primary mb-3">Add Entrance Fee</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Duration</th>
                <th>Fees</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entranceFees as $fee)
                <tr>
                    <td>{{ $fee->duration }}</td>
                    <td>{{ $fee->fees }}</td>
                    <td>{{ $fee->type }}</td>
                    <td>
                        <a href="{{ route('entrance-fees.edit', $fee->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('entrance-fees.destroy', $fee->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirm delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
