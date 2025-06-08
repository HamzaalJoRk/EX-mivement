<div style="direction: rtl">
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-12 mb-3">
                <label for="name">الاسم:</label>
                <input wire:model="name" type="text" id="name" name="name"
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                    placeholder="Name" required>
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div class="col-span-12 mb-3">
                <label for="permissions" class="mb-1">الصلاحيات:</label>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">
                                الاسم
                            </th>
                            <th scope="col">
                                التحديد
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>
                                    {{ $permission->name }}
                                </td>
                                <td>
                                    <label>
                                        <input wire:model="selectedPermissions" type="checkbox" name="selectedPermissions[]"
                                            value="{{ $permission->id }}">
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @error('selectedPermissions') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
            <div class="col-span-12 mb-3 text-center">
                <button type="submit"
                        class="btn btn-success mb-4">
                    حفظ
                </button>
            </div>
        </div>
    </form>
</div>