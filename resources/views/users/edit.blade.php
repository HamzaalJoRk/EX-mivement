@extends('layouts.app')

@section('content')
    <div class="container mt-1">
        <div class="card shadow rounded">
            <div class="card-header text-center font-weight-bold">
                <h4>تعديل المستخدم: {{ $user->name }}</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- رسائل الخطأ --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>حدث خطأ!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- رسائل النجاح --}}
                    @if (session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group mb-2">
                        <label class="mb-1" for="name">الاسم:</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control" placeholder="Name" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="mb-1" for="email">الايميل:</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control" placeholder="Email" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="mb-1" for="password">كلمة المرور (اتركها فارغة إن لم ترد التغيير):</label>
                        <input type="password" id="password" name="password"
                               class="form-control" placeholder="Password">
                    </div>

                    <div class="form-group mb-2">
                        <label class="mb-1" for="confirm-password">تأكيد كلمة المرور:</label>
                        <input type="password" id="confirm-password" name="password_confirmation"
                               class="form-control" placeholder="Confirm Password">
                    </div>

                    <div class="form-group mb-2">
                        <label class="mb-1" for="border_crossing_id">المعبر الحدودي:</label>
                        <select id="border_crossing_id" name="border_crossing_id" class="form-control" required>
                            <option value="" disabled>اختر المعبر</option>
                            @foreach($borderCrossings as $crossing)
                                <option value="{{ $crossing->id }}"
                                    {{ old('border_crossing_id', $user->border_crossing_id) == $crossing->id ? 'selected' : '' }}>
                                    {{ $crossing->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label class="mb-1" for="roles" class="form-label">الصلاحيات:</label>
                        <select name="roles[]" id="roles" class="form-control" multiple required>
                            @foreach ($allRoles as $roleName => $roleLabel)
                                <option value="{{ $roleName }}"
                                    {{ in_array($roleName, old('roles', $userRole)) ? 'selected' : '' }}>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">اضغط Ctrl لتحديد أكثر من صلاحية</small>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
