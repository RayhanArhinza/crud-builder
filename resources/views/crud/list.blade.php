@extends('includes.app')

@section('content')
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
      <i class="fas fa-cogs mr-2"></i>CRUD Builder
    </h1>
    <a href="{{ route('crud.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg shadow-lg transition-transform transform hover:scale-105">
      <i class="fas fa-plus mr-2"></i> Create New Table
    </a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($tables as $table)
  <div class="bg-white border border-gray-300 rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ ucfirst($table->name) }}</h2>
    <a href="{{ route('table.index', $table->name) }}" class="text-blue-600 hover:text-blue-800">Manage Data</a>
    <div class="flex space-x-4 mt-4">
        <a href="{{ route('crud.edit', $table->id) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>

        <form action="{{ route('crud.destroy', $table->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>

    </div>
  </div>
@endforeach

  </div>

  <!-- Chart.js integration -->
  <div class="my-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Table Data Visualization</h2>
    <canvas id="tableChart" width="100%" height="30"></canvas>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('tableChart').getContext('2d');
    const tableData = @json($tableData); // Assuming you pass the table data as an array

    const tableChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: tableData.map(item => item.name),
        datasets: [{
          label: 'Number of Entries',
          data: tableData.map(item => item.entries), // Assuming 'entries' field contains the count of entries
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
@endsection
