@extends('layouts.app')

@section('content')
    <h1 class="mb-1">قائمة المستخدمين</h1>

    <a href="/user-create" class="btn btn-primary">
        اضافة مستخدم
    </a>
    <table class="table table-bordered mt-1">
        <thead>
            <tr class="flex">
                <th scope="col">
                    الاسم
                </th>
                <th scope="col">
                    البريد الالكتروني
                </th>
                <th scope="col">
                    المعبر
                </th>
                <th scope="col">
                    الصلاحية
                </th>
                <th scope="col">
                    السجل
                </th>
                <th scope="col">
                    اجراء
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>
                        {{ $user->email }}
                    </td>
                    <td>
                        {{ $user->borderCrossing->name ?? 'مدير' }}
                    </td>
                    <td>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-secondary text-dark">
                                    @if ($v == 'Admin')
                                        المدير العام
                                    @elseif ($v == 'CustomEntry')
                                        جمارك دخول
                                    @elseif ($v == 'CustomExit')
                                        جمارك خروج
                                    @elseif ($v == 'babExit')
                                        موظف باب خروج
                                    @elseif ($v == 'Finance')
                                        موظف مالية
                                    @elseif ($v == 'babEntry')
                                        موظف باب دخول
                                    @else
                                        {{ $v }}
                                    @endif
                                </label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('user.logs', $user->id) }}" class="btn btn-sm btn-outline-primary">
                            سجل العمليات
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-info btn-sm">
                            تعديل
                        </a>
                        @if (!$user->hasRole('Admin'))
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    حذف
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection