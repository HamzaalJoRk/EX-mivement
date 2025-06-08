@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif
    <div class="py-10" style="direction: rtl">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Permission</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($permissions as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td>
                    <div>
                        <a href="{{ route('permissions.edit', ['permission' => $permission->id]) }}" class="btn btn-primary">Edit</a>
                        <form method="POST" action="{{ route('permissions.destroy', $permission->id) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection