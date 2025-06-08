@extends('layouts.app')

@section('content')
<x-admin-dash-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Role Management
        </h2>
        
        <a href="{{ route('roles.index') }}" class="px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            Back
        </a>
    </div>
</x-slot>
@if(session('success'))
    <div class="mb-4">
        <div class="font-medium text-green-600">
            {{ session('success') }}
        </div>
    </div>
@endif
    <div>
  @livewire('create-role')
  
</x-admin-dash-layout>

@endsection