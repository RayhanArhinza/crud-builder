@extends('includes.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Table</h1>
        <p class="mt-2 text-gray-600">Modify the structure of the table</p>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('crud.update', $table->id) }}" method="POST">
            @csrf
            @method('PUT')

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
                            value="{{ old('name', $table->name) }}"
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
                    @foreach($table->columns as $index => $column)
                    <div class="column-row bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Column Name -->
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Column Name</label>
                                <input type="text"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       name="columns[{{ $index }}][name]"
                                       value="{{ old('columns.' . $index . '.name', $column->name) }}"
                                       required
                                       placeholder="Enter column name">
                            </div>

                            <!-- Column Type -->
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Data Type</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        name="columns[{{ $index }}][type]"
                                        required>
                                    <option value="string" {{ $column->type == 'string' ? 'selected' : '' }}>String</option>
                                    <option value="integer" {{ $column->type == 'integer' ? 'selected' : '' }}>Integer</option>
                                    <option value="text" {{ $column->type == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="boolean" {{ $column->type == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                    <option value="date" {{ $column->type == 'date' ? 'selected' : '' }}>Date</option>
                                    <option value="datetime" {{ $column->type == 'datetime' ? 'selected' : '' }}>DateTime</option>
                                    <option value="decimal" {{ $column->type == 'decimal' ? 'selected' : '' }}>Decimal</option>
                                </select>
                            </div>

                            <!-- Is Relation -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Is Relation</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors is-relation-select"
                                        name="columns[{{ $index }}][is_relation]"
                                        data-index="{{ $index }}">
                                    <option value="0" {{ $column->is_relation == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $column->is_relation == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>

                            <!-- Input Type -->
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Input Type</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        name="columns[{{ $index }}][input_type]"
                                        required>
                                    <option value="text" {{ $column->input_type == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="password" {{ $column->input_type == 'password' ? 'selected' : '' }}>Password</option>
                                    <option value="email" {{ $column->input_type == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="number" {{ $column->input_type == 'number' ? 'selected' : '' }}>Number</option>
                                    <option value="tel" {{ $column->input_type == 'tel' ? 'selected' : '' }}>Telephone</option>
                                    <option value="url" {{ $column->input_type == 'url' ? 'selected' : '' }}>URL</option>
                                    <option value="date" {{ $column->input_type == 'date' ? 'selected' : '' }}>Date</option>
                                    <option value="time" {{ $column->input_type == 'time' ? 'selected' : '' }}>Time</option>
                                    <option value="datetime-local" {{ $column->input_type == 'datetime-local' ? 'selected' : '' }}>DateTime</option>
                                    <option value="textarea" {{ $column->input_type == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                    <option value="checkbox" {{ $column->input_type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                    <option value="radio" {{ $column->input_type == 'radio' ? 'selected' : '' }}>Radio</option>
                                    <option value="select" {{ $column->input_type == 'select' ? 'selected' : '' }}>Select</option>
                                    <option value="file" {{ $column->input_type == 'file' ? 'selected' : '' }}>File Upload</option>
                                    <option value="color" {{ $column->input_type == 'color' ? 'selected' : '' }}>Color Picker</option>
                                    <option value="range" {{ $column->input_type == 'range' ? 'selected' : '' }}>Range Slider</option>
                                    <option value="hidden" {{ $column->input_type == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                    <option value="search" {{ $column->input_type == 'search' ? 'selected' : '' }}>Search</option>
                                    <option value="month" {{ $column->input_type == 'month' ? 'selected' : '' }}>Month</option>
                                    <option value="week" {{ $column->input_type == 'week' ? 'selected' : '' }}>Week</option>
                                </select>
                            </div>

                            <!-- Related Table -->
                            <div class="md:col-span-3 related-table-container" id="related-table-container-{{ $index }}" style="display: {{ $column->is_relation ? 'block' : 'none' }};">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Related Table</label>
                                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        name="columns[{{ $index }}][related_table_id]">
                                    <option value="">Select Table</option>
                                    @foreach($existingTables as $table)
                                        <option value="{{ $table->id }}" {{ $column->related_table_id == $table->id ? 'selected' : '' }}>{{ $table->name }}</option>
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
                    @endforeach
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
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the highest index from existing columns
    let columnIndex = document.querySelectorAll('.column-row').length - 1;

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
