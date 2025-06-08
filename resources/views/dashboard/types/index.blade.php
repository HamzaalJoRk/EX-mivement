@extends('layouts.app')

@section('content')
<div class="container">
    <h2>cars type</h2>
    <a href="{{ route('entrance-fees.create') }}" class="btn btn-primary mb-3">Add Car type</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Duration</th>
                <th>Fees</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($types as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>
                        <a href="{{ route('types.edit', $type->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('types.destroy', $type->id) }}" method="POST" style="display:inline;">
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
