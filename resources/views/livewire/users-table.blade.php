<div class="py-10" style="direction: rtl">
    <table class="table mt-4">
        <thead>
            <tr class="flex">
                <th scope="col">
                    الاسم
                    
                </th>
                <th scope="col">
                    البريد
                    
                </th>
                <th scope="col">
                    الصلاحية
                
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
                @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $v)
                        <label class="badge badge-secondary text-dark">{{ $v }}</label>
                    @endforeach
                @endif
                </td>
        
                <td>
                
                
                
                
                <div>
                    <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-primary">
                        تعديل
                    </a>
                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
