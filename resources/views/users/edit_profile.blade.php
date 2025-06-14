@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">تعديل كلمة المرور</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update_password') }}">
        @csrf

        <div class="form-group mb-3">
            <label>الاسم</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="form-group mb-3">
            <label>البريد الإلكتروني</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>

        <div class="form-group mb-3">
            <label>كلمة المرور الجديدة</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group mb-3">
            <label>تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">تحديث كلمة المرور</button>
    </form>
</div>
@endsection
