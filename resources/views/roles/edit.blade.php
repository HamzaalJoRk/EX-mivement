@extends('layouts.app')

@section('content')
<a href="{{ route('roles.index') }}" class="px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
    Back
</a>
@if(session('success'))
    <div class="mb-4">
        <div class="font-medium text-green-600">
            {{ session('success') }}
        </div>
    </div>
@endif
  
@livewire('edit-role', ['id' => $role->id])
@endsection

