
<div class="py-10" style="direction: rtl">
            
    <table class="table mt-4">
        <thead>
            <tr>
                <th scope="col">
                    الصلاحية
                </th>
                <th scope="col">
                    اجراء
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>
                    {{ $role->name }}
                    </td>             
                    <td>
                        <div>
                        <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-primary">
                            تعديل
                        </a>
                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}" onsubmit="return confirm('Are you sure you want to delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                حذف
                            </button>
                        </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
