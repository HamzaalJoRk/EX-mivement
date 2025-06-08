@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>تعديل حركة دخول</h1>

        <form action="{{ route('entry_statements.update', $entryStatement->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('dashboard.entry_statements.form', ['entry_statement' => $entryStatement])

            <button type="submit" class="btn btn-primary mt-3">تحديث</button>
        </form>
    </div>
@endsection
