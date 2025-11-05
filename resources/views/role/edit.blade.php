<div class="modal-header mb-4">
    <h3 class="text-xl font-semibold text-gray-900">Edit Role</h3>
    <button type="button" class="close-modal absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
        <i class="fas fa-times"></i>
    </button>
</div>

<form action="{{ route('role.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Role Name <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" required>
        @error('name')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end mt-6 gap-2">
        <button type="button" class="close-modal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Cancel
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Update Role
        </button>
    </div>
</form>
