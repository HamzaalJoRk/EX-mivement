@extends('layouts.app')

@section('content')
    <h1 class="mb-1">قائمة الصلاحيات</h1>
    <a href="/user-create" class="btn btn-primary mb-2">
        اضافة مستخدم
    </a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th scope="col">
                    الصلاحية
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>
                        @if ($role->name == 'Admin')
                            المدير العام
                        @elseif ($role->name == 'CustomEntry')
                            جمارك دخول
                        @elseif ($role->name == 'CustomExit')
                            جمارك خروج
                        @elseif ($role->name == 'babExit')
                            موظف باب خروج
                        @elseif ($role->name == 'Finance')
                            موظف مالية
                        @elseif ($role->name == 'babEntry')
                            موظف باب دخول
                        @else
                            {{ $role->name }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection