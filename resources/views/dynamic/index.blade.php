@extends('includes.app')

@section('header', 'Table: ' . ucfirst($tableName))

@section('content')
<div class="space-y-6">
    <!-- API Information Card -->
    @php
        $apiRoute = \App\Models\ApiRoute::whereHas('crudTable', function($query) use ($tableName) {
            $query->where('name', $tableName);
        })->first();
    @endphp
    @if($apiRoute)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="border-b border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-code mr-2 text-purple-500"></i>
                    API Endpoints
                </h3>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-600 mb-2">Authentication Header</h4>
                    <div class="flex items-center">
                        <code class="bg-gray-100 p-2 text-sm rounded flex-grow">X-API-TOKEN: {{ $apiRoute->api_token }}</code>
                        <button onclick="copyToClipboard('{{ $apiRoute->api_token }}')" class="ml-2 p-2 text-gray-500 hover:text-blue-500">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- GET All -->
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded">GET</span>
                            <button onclick="copyToClipboard('{{ url($apiRoute->endpoint) }}')" class="text-gray-500 hover:text-blue-500">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <code class="block text-sm">{{ url($apiRoute->endpoint) }}</code>
                        <p class="mt-2 text-xs text-gray-600">Fetch all records</p>
                    </div>

                    <!-- GET Single -->
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded">GET</span>
                            <button onclick="copyToClipboard('{{ url($apiRoute->endpoint) }}/{id}')" class="text-gray-500 hover:text-blue-500">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <code class="block text-sm">{{ url($apiRoute->endpoint) }}/{id}</code>
                        <p class="mt-2 text-xs text-gray-600">Fetch a single record</p>
                    </div>

                    <!-- POST -->
                    <div class="p-4 bg-green-50 border border-green-100 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="px-2 py-1 bg-green-500 text-white text-xs font-medium rounded">POST</span>
                            <button onclick="copyToClipboard('{{ url($apiRoute->endpoint) }}')" class="text-gray-500 hover:text-blue-500">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <code class="block text-sm">{{ url($apiRoute->endpoint) }}</code>
                        <p class="mt-2 text-xs text-gray-600">Create a new record</p>
                    </div>

                    <!-- PUT -->
                    <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-medium rounded">PUT</span>
                            <button onclick="copyToClipboard('{{ url($apiRoute->endpoint) }}/{id}')" class="text-gray-500 hover:text-blue-500">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <code class="block text-sm">{{ url($apiRoute->endpoint) }}/{id}</code>
                        <p class="mt-2 text-xs text-gray-600">Update an existing record</p>
                    </div>

                    <!-- DELETE -->
                    <div class="p-4 bg-red-50 border border-red-100 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="px-2 py-1 bg-red-500 text-white text-xs font-medium rounded">DELETE</span>
                            <button onclick="copyToClipboard('{{ url($apiRoute->endpoint) }}/{id}')" class="text-gray-500 hover:text-blue-500">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <code class="block text-sm">{{ url($apiRoute->endpoint) }}/{id}</code>
                        <p class="mt-2 text-xs text-gray-600">Delete a record</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Add New Data Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="border-b border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-blue-500"></i>
                    Add New Data
                </h3>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('table.store', $tableName) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($columns as $column)
                        <div class="space-y-2">
                            <label for="{{ $column->name }}" class="block text-sm font-medium text-gray-800">
                                {{ ucfirst($column->name) }}:
                            </label>

                            {{-- If column is a relation, display dropdown --}}
                            @if($column->is_relation && isset($relatedTableData[$column->name]))
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    id="{{ $column->name }}"
                                    name="{{ $column->name }}">
                                    <option value="">-- Select {{ $column->relatedTable->name ?? 'related' }} --</option>
                                    @foreach($relatedTableData[$column->name] as $relatedItem)
                                        <option value="{{ $relatedItem->id }}">
                                            {{ $relatedItem->id }} - {{ isset($relatedItem->name) ? $relatedItem->name : $relatedItem->created_at }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                @php
                                    $actualInputType = $column->input_type;

                                    if ($column->type === 'date') {
                                        $actualInputType = 'date';
                                    } elseif ($column->type === 'datetime') {
                                        $actualInputType = 'datetime-local';
                                    } elseif ($column->type === 'time') {
                                        $actualInputType = 'time';
                                    } elseif ($column->type === 'boolean') {
                                        $actualInputType = 'checkbox';
                                    } elseif (in_array($column->type, ['text', 'longtext'])) {
                                        $actualInputType = 'textarea';
                                    }
                                @endphp

                                @if($actualInputType === 'textarea')
                                    <textarea
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        id="{{ $column->name }}"
                                        name="{{ $column->name }}"
                                        rows="3"
                                        placeholder="Enter {{ $column->name }}"></textarea>
                                @elseif($actualInputType === 'select')
                                    <select
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        id="{{ $column->name }}"
                                        name="{{ $column->name }}">
                                        <option value="">-- Select Option --</option>
                                        {{-- Add specific options here if needed --}}
                                    </select>
                                @elseif($actualInputType === 'radio')
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio"
                                                   id="{{ $column->name }}_1"
                                                   name="{{ $column->name }}"
                                                   value="1"
                                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="{{ $column->name }}_1" class="text-gray-700">Option 1</label>
                                        </div>
                                    </div>
                                @elseif($actualInputType === 'checkbox')
                                    <div class="flex items-center">
                                        {{-- Include hidden input to send default value (e.g. 0) --}}
                                        <input type="hidden" name="{{ $column->name }}" value="0">
                                        <input
                                            type="checkbox"
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            id="{{ $column->name }}"
                                            name="{{ $column->name }}"
                                            value="1">
                                        <label for="{{ $column->name }}" class="ml-2 block text-sm text-gray-700">Yes</label>
                                    </div>
                                @elseif($actualInputType === 'file')
                                    <input
                                        type="file"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        id="{{ $column->name }}"
                                        name="{{ $column->name }}">
                                @else
                                    <input
                                        type="{{ $actualInputType }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        id="{{ $column->name }}"
                                        name="{{ $column->name }}"
                                        placeholder="Enter {{ $column->name }}"
                                        @if($actualInputType === 'number')
                                            step="any"
                                        @endif
                                        @if($actualInputType === 'color')
                                            value="#000000"
                                        @endif
                                        @if($actualInputType === 'date')
                                            value="{{ date('Y-m-d') }}"
                                        @endif
                                        @if($actualInputType === 'datetime-local')
                                            value="{{ date('Y-m-d\TH:i') }}"
                                        @endif>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data List Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 p-6 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list mr-2 text-blue-500"></i>
                Data List
            </h3>
            <a href="{{ route('table.export', $tableName) }}?search={{ request('search') }}"
            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>

        <div class="p-6">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('table.index', $tableName) }}" class="mb-4">
                <div class="flex items-center space-x-2">
                    <div class="relative w-full">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search data..."
                               class="w-full pl-4 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200 ease-in-out">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.387a1 1 0 01-1.414 1.414l-4.387-4.387zm-4.9.68a6 6 0 100-12 6 6 0 000 12z" />
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out shadow flex items-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            @foreach($columns as $column)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ ucfirst($column->name) }}
                                </th>
                            @endforeach
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $row->id }}</td>
                                @foreach($columns as $column)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($column->is_relation && $column->related_table_id)
                                            {{ $row->{$column->name . '_name'} ?? '-' }}
                                        @elseif($column->type === 'boolean')
                                            {!! $row->{$column->name} ? '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Yes</span>' : '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">No</span>' !!}
                                        @elseif($column->type === 'text' || $column->type === 'longtext')
                                            {{ \Illuminate\Support\Str::limit($row->{$column->name}, 50) }}
                                        @else
                                            {{ $row->{$column->name} }}
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('table.edit', [$tableName, $row->id]) }}"
                                           class="bg-yellow-500 text-white hover:bg-yellow-700 px-4 py-2 rounded flex items-center">
                                            <i class="fas fa-edit mr-2"></i> Edit
                                        </a>
                                        <form action="{{ route('table.destroy', [$tableName, $row->id]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this data?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white hover:bg-red-700 px-4 py-2 rounded flex items-center">
                                                <i class="fas fa-trash mr-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if(count($data) === 0)
                            <tr>
                                <td colspan="{{ count($columns) + 2 }}" class="text-center py-3">No data available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-4 p-2 flex justify-end">
                {{ $data->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>

{{-- Add JavaScript function for copy to clipboard functionality --}}
<script>
function copyToClipboard(text) {
    const el = document.createElement('textarea');
    el.value = text;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);

    // Show a temporary tooltip or notification
    const notification = document.createElement('div');
    notification.textContent = 'Copied to clipboard';
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.padding = '10px 15px';
    notification.style.backgroundColor = '#4CAF50';
    notification.style.color = 'white';
    notification.style.borderRadius = '4px';
    notification.style.zIndex = '9999';
    notification.style.transition = 'opacity 0.5s';

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 2000);
}
</script>
@endsection
