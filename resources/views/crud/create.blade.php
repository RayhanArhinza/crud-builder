@extends('includes.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Create New Table</h1>
        <p class="mt-2 text-gray-600">Design your database table structure with an easy-to-use interface</p>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('crud.store') }}" method="POST">
            @csrf

            <!-- Table Name Section -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Table Information</h2>
                <div class="max-w-2xl">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Table Name
                    </label>
                    <div class="relative rounded-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-table text-gray-400"></i>
                        </div>
                        <input
                            type="text"
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            id="name"
                            name="name"
                            placeholder="Enter table name"
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Columns Section -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Table Columns</h2>
                    <button type="button"
                            id="add-column"
                            class="flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-200 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Add Column
                    </button>
                </div>

                <div id="columns-container" class="space-y-6">
                    <div class="column-row bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Column Name -->
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Column Name</label>
                                <input type="text"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       name="columns[0][name]"
                                       required
                                       placeholder="Enter column name">
                            </div>

                            <!-- Column Type -->
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Data Type</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        name="columns[0][type]"
                                        required>
                                    <option value="string">String</option>
                                    <option value="integer">Integer</option>
                                    <option value="text">Text</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="date">Date</option>
                                    <option value="datetime">DateTime</option>
                                    <option value="decimal">Decimal</option>
                                </select>
                            </div>

                            <!-- Is Relation -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Is Relation</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors is-relation-select"
                                        name="columns[0][is_relation]"
                                        data-index="0">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <!-- Input Type -->
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Input Type</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        name="columns[0][input_type]"
                                        required>
                                    <option value="text">Text</option>
                                    <option value="password">Password</option>
                                    <option value="email">Email</option>
                                    <option value="number">Number</option>
                                    <option value="tel">Telephone</option>
                                    <option value="url">URL</option>
                                    <option value="date">Date</option>
                                    <option value="time">Time</option>
                                    <option value="datetime-local">DateTime</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="radio">Radio</option>
                                    <option value="select">Select</option>
                                    <option value="file">File Upload</option>
                                    <option value="color">Color Picker</option>
                                    <option value="range">Range Slider</option>
                                    <option value="hidden">Hidden</option>
                                    <option value="search">Search</option>
                                    <option value="month">Month</option>
                                    <option value="week">Week</option>
                                </select>
                            </div>

                            <!-- Related Table -->
                            <div class="md:col-span-3 related-table-container" id="related-table-container-0" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Related Table</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        name="columns[0][related_table_id]">
                                    <option value="">Select Table</option>
                                    @foreach($existingTables as $table)
                                        <option value="{{ $table->id }}">{{ $table->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Remove Button -->
                            <div class="md:col-span-12 flex justify-end">
                                <button type="button"
                                        class="remove-column inline-flex items-center px-3 py-1 text-sm text-red-600 hover:text-red-500 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('crud.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-200 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Create Table
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let columnIndex = 0;

    // Add column button click
    document.getElementById('add-column').addEventListener('click', function() {
        columnIndex++;
        const newColumn = document.querySelector('.column-row').cloneNode(true);

        // Update input names
        newColumn.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${columnIndex}]`));
            }
        });

        // Update relation select
        const relationSelect = newColumn.querySelector('.is-relation-select');
        if (relationSelect) {
            relationSelect.setAttribute('data-index', columnIndex);
        }

        // Update related table container
        const relatedTableContainer = newColumn.querySelector('.related-table-container');
        if (relatedTableContainer) {
            relatedTableContainer.setAttribute('id', `related-table-container-${columnIndex}`);
            relatedTableContainer.style.display = 'none';
        }

        // Clear inputs
        newColumn.querySelectorAll('input').forEach(input => input.value = '');
        newColumn.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Add with animation
        newColumn.style.opacity = '0';
        document.getElementById('columns-container').appendChild(newColumn);
        requestAnimationFrame(() => {
            newColumn.style.transition = 'opacity 0.3s ease';
            newColumn.style.opacity = '1';
        });

        setupRelationSelectListener(relationSelect);
    });

    // Remove column
    document.getElementById('columns-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-column')) {
            const columnRow = e.target.closest('.column-row');
            if (document.querySelectorAll('.column-row').length > 1) {
                columnRow.style.opacity = '0';
                columnRow.style.transform = 'translateX(20px)';
                columnRow.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                setTimeout(() => columnRow.remove(), 300);
            }
        }
    });

    // Setup relation select listeners
    function setupRelationSelectListener(select) {
        select.addEventListener('change', function() {
            const index = this.getAttribute('data-index');
            const container = document.getElementById(`related-table-container-${index}`);
            const isVisible = this.value === '1';

            container.style.display = isVisible ? 'block' : 'none';
            if (isVisible) {
                container.style.opacity = '0';
                requestAnimationFrame(() => {
                    container.style.transition = 'opacity 0.3s ease';
                    container.style.opacity = '1';
                });
            }
        });
    }

    document.querySelectorAll('.is-relation-select').forEach(setupRelationSelectListener);
});
</script>
@endsection
