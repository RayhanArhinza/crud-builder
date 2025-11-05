@extends('includes.app')

@section('content')
    <div class="container-fluid">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Role Management</h1>
                <button id="addRoleButton" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Add New Role
                </button>
            </div>

            <!-- Search and Filters -->
            <div class="mb-6">
                <form action="{{ route('role.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"><i class="fas fa-search mr-2"></i>Search</button>
                    <a href="{{ route('role.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Reset</a>
                </form>
            </div>

            <!-- Roles Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($roles as $index => $role)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + $roles->firstItem() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $role->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <button data-id="{{ $role->id }}" class="edit-role-button bg-yellow-500 text-white hover:bg-yellow-700 px-4 py-2 rounded flex items-center">
                                            <i class="fas fa-edit mr-2"></i>
                                            <span>Edit</span>
                                        </button>

                                        <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                class="delete-role-button bg-red-500 text-white hover:bg-red-700 px-4 py-2 rounded flex items-center"
                                                data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}">
                                                <i class="fas fa-trash-alt mr-2"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No roles found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    <!-- Add Role Modal Container -->
    <div id="addRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div id="addRoleModalContent">
                <!-- Content will be loaded from create.blade.php -->
            </div>
        </div>
    </div>

    <!-- Edit Role Modal Container -->
    <div id="editRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div id="editRoleModalContent">
                <!-- Content will be loaded from edit.blade.php -->
            </div>
        </div>
    </div>
    <div id="deleteRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Role</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete the role "<span id="roleNameToDelete"></span>"? This action cannot be undone.
                    </p>
                </div>
                <div class="flex justify-center gap-4 mt-4">
                    <button id="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                    <button class="close-modal bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Previous modal code remains the same

            // Delete Role Modal
            const deleteRoleModal = document.getElementById('deleteRoleModal');
            const roleNameToDelete = document.getElementById('roleNameToDelete');
            const deleteButtons = document.querySelectorAll('.delete-role-button');
            const confirmDeleteButton = document.getElementById('confirmDelete');
            let deleteForm = null;

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const roleId = this.getAttribute('data-id');
                    const roleName = this.getAttribute('data-name');

                    // Create the form that will be submitted
                    deleteForm = document.createElement('form');
                    deleteForm.method = 'POST';
                    deleteForm.action = `{{ url('role') }}/${roleId}`;
                    deleteForm.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;

                    // Update modal content
                    roleNameToDelete.textContent = roleName;
                    deleteRoleModal.classList.remove('hidden');
                });
            });

            // Handle delete confirmation
            confirmDeleteButton.addEventListener('click', function() {
                if (deleteForm) {
                    document.body.appendChild(deleteForm);
                    deleteForm.submit();
                }
            });

            // Setup close handlers for delete modal
            const deleteModalCloseButtons = deleteRoleModal.querySelectorAll('.close-modal');
            deleteModalCloseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    deleteRoleModal.classList.add('hidden');
                });
            });

            // Update window click handler to include delete modal
            window.addEventListener('click', function(event) {
                if (event.target === deleteRoleModal) {
                    deleteRoleModal.classList.add('hidden');
                }
                // Previous modal close handlers remain the same
            });

            // Update escape key handler to include delete modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    deleteRoleModal.classList.add('hidden');
                    // Previous modal close handlers remain the same
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
    // Add Role Modal
    const addRoleModal = document.getElementById('addRoleModal');
    const addRoleButton = document.getElementById('addRoleButton');
    const addRoleModalContent = document.getElementById('addRoleModalContent');

    addRoleButton.addEventListener('click', function() {
        // Load create form content via AJAX
        fetch('{{ route('role.create') }}')
            .then(response => response.text())
            .then(html => {
                addRoleModalContent.innerHTML = html;
                addRoleModal.classList.remove('hidden');
                setupModalCloseHandlers(addRoleModal, addRoleModalContent);
            })
            .catch(error => console.error('Error loading create form:', error));
    });

    // Edit Role Modal
    const editRoleModal = document.getElementById('editRoleModal');
    const editRoleModalContent = document.getElementById('editRoleModalContent');
    const editButtons = document.querySelectorAll('.edit-role-button');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const roleId = this.getAttribute('data-id');

            // Load edit form content via AJAX
            fetch(`{{ url('role') }}/${roleId}/edit`)
                .then(response => response.text())
                .then(html => {  // Fixed typo here from 'hstml' to 'html'
                    editRoleModalContent.innerHTML = html;
                    editRoleModal.classList.remove('hidden');
                    setupModalCloseHandlers(editRoleModal, editRoleModalContent);
                })
                .catch(error => console.error('Error loading edit form:', error));
        });
    });

    // Helper function to setup modal close handlers
    function setupModalCloseHandlers(modal, modalContent) {
        // Setup close button event for the loaded content
        const closeButtons = modalContent.querySelectorAll('.close-modal');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === addRoleModal) {
            addRoleModal.classList.add('hidden');
        }
        if (event.target === editRoleModal) {
            editRoleModal.classList.add('hidden');
        }
    });

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            addRoleModal.classList.add('hidden');
            editRoleModal.classList.add('hidden');
        }
    });
});
    </script>
@endsection
