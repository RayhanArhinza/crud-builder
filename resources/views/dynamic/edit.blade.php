@extends('includes.app')

@section('header', 'Edit ' . ucfirst($tableName))

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="border-b border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-edit mr-2 text-blue-500"></i>
                Edit Data (ID: {{ $row->id }})
            </h3>
        </div>
    </div>
    <div class="p-6">
        <form action="{{ route('table.update', [$tableName, $row->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($columns as $column)
                    <div class="space-y-2">
                        <label for="{{ $column->name }}" class="block text-sm font-medium text-gray-700">
                            {{ ucfirst($column->name) }}:
                        </label>

                        {{-- Jika kolom merupakan relasi, tampilkan dropdown --}}
                        @if($column->is_relation && isset($relatedTableData[$column->name]))
                            <select
                                class="form-input w-full rounded-lg border-2 border-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2 px-3 text-base"
                                id="{{ $column->name }}"
                                name="{{ $column->name }}">
                                <option value="">-- Select {{ $column->relatedTable->name }} --</option>
                                @foreach($relatedTableData[$column->name] as $relatedItem)
                                    <option value="{{ $relatedItem->id }}" {{ $row->{$column->name} == $relatedItem->id ? 'selected' : '' }}>
                                        {{ $relatedItem->id }} - {{ isset($relatedItem->name) ? $relatedItem->name : $relatedItem->created_at }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            @php
                                // Menentukan tipe input yang sebenarnya
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
                                    class="form-input w-full rounded-lg border-2 border-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2 px-3 text-base"
                                    id="{{ $column->name }}"
                                    name="{{ $column->name }}"
                                    rows="3">{{ $row->{$column->name} }}</textarea>
                            @elseif($actualInputType === 'select')
                                <select
                                    class="form-input w-full rounded-lg border-2 border-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2 px-3 text-base"
                                    id="{{ $column->name }}"
                                    name="{{ $column->name }}">
                                    <option value="">-- Select Option --</option>
                                    {{-- Jika ada opsi spesifik, tambahkan di sini --}}
                                </select>
                            @elseif($actualInputType === 'radio')
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input type="radio"
                                               id="{{ $column->name }}_1"
                                               name="{{ $column->name }}"
                                               value="1"
                                               {{ $row->{$column->name} == 1 ? 'checked' : '' }}
                                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="{{ $column->name }}_1">Option 1</label>
                                    </div>
                                </div>
                            @elseif($actualInputType === 'checkbox')
                                {{-- Sertakan input hidden untuk mengirim nilai default (misalnya 0) --}}
                                <input type="hidden" name="{{ $column->name }}" value="0">
                                <input
                                    type="checkbox"
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                    id="{{ $column->name }}"
                                    name="{{ $column->name }}"
                                    value="1"
                                    {{ $row->{$column->name} ? 'checked' : '' }}>
                            @elseif($actualInputType === 'file')
                                <input
                                    type="file"
                                    class="form-input w-full rounded-lg border-2 border-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2 px-3 text-base"
                                    id="{{ $column->name }}"
                                    name="{{ $column->name }}">
                            @else
                                <input
                                    type="{{ $actualInputType }}"
                                    class="form-input w-full rounded-lg border-2 border-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-200 py-2 px-3 text-base"
                                    id="{{ $column->name }}"
                                    name="{{ $column->name }}"
                                    value="{{ $row->{$column->name} }}"
                                    @if($actualInputType === 'number')
                                        step="any"
                                    @endif
                                    @if($actualInputType === 'color')
                                        value="#000000"
                                    @endif>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
