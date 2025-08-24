@extends('layouts.app')

@section('content')
    <div class="container mt-1">
        <div class="card shadow rounded">
            <div class="card-header text-center font-weight-bold">
                <h4>إضافة مستخدم جديد</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

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

                    @if (session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="mb-1" for="name">الاسم:</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   class="form-control" placeholder="Name" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="mb-1" for="email">الايميل:</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="Email" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="mb-1" for="password">كلمة المرور:</label>
                            <input type="password" id="password" name="password"
                                   class="form-control" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="mb-1" for="confirm-password">تأكيد كلمة المرور:</label>
                            <input type="password" id="confirm-password" name="password_confirmation"
                                   class="form-control" placeholder="Confirm Password" required>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="mb-1" for="border_crossing_id">المعبر الحدودي:</label>
                            <select id="border_crossing_id" name="border_crossing_id" class="form-control" required>
                                <option value="" disabled selected>اختر المعبر</option>
                                @foreach($borderCrossings as $crossing)
                                    <option value="{{ $crossing->id }}" {{ old('border_crossing_id') == $crossing->id ? 'selected' : '' }}>
                                        {{ $crossing->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="mb-1" for="roles" class="form-label">الصلاحيات:</label>
                            <select name="roles[]" id="roles" class="form-control" required>
                                @foreach ($allRoles as $roleName => $roleLabel)
                                    <option value="{{ $roleName }}" {{ in_array($roleName, old('roles', [])) ? 'selected' : '' }}>
                                        @if ($roleLabel == 'Admin')
                                            المدير العام
                                        @elseif ($roleLabel == 'CustomEntry')
                                            جمارك دخول
                                        @elseif ($roleLabel == 'CustomExit')
                                            جمارك خروج
                                        @elseif ($roleLabel == 'babExit')
                                            موظف باب خروج
                                        @elseif ($roleLabel == 'Finance')
                                            موظف مالية
                                        @elseif ($roleLabel == 'babEntry')
                                            موظف باب دخول
                                        @else
                                            {{ $roleLabel }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-5">إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
