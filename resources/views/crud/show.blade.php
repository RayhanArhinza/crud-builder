@extends('includes.app')

@section('content')
  <h1 class="text-2xl font-bold mb-4">{{ ucfirst($tableName) }} Data</h1>

  <div class="mb-4">
    <a href="{{ route('crud.list') }}" class="text-blue-500 hover:underline">← Back to Tables</a>
  </div>

  <!-- Tabel data dan form input -->
  <!-- ... kode lainnya ... -->

  <form action="{{ route('table.store', $tableName) }}" method="POST">
    @csrf
    <div class="grid grid-cols-2 gap-4">
        @foreach($columns as $col)
            <div>
                <label class="block mb-1">{{ ucfirst($col->name) }}</label>
                <input type="text" name="{{ $col->name }}" class="border p-2 w-full" placeholder="Enter {{ $col->name }}">
            </div>
        @endforeach
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Submit</button>
  </form>
@endsection
