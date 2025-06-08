<div style="direction: rtl">
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="name" class="block text-sm font-medium text-gray-700">Permission Name:</label>
            <input wire:model="name" type="text" id="name" name="name"
                   class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                   placeholder="Enter permission name" required>
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3 text-center">
            <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                Update Permission
            </button>
        </div>
    </form>
</div>
