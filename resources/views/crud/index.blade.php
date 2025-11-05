@extends('includes.app')

@section('content')
<!-- CRUD Builder Section -->
<div class="bg-white p-6 rounded-xl shadow-lg mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 px-4 sm:px-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-0">
            <i class="fas fa-table mr-2"></i>Table List
        </h1>
        <a href="{{ route('crud.create') }}" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg shadow-lg transition-transform transform hover:scale-105 text-center">
            <i class="fas fa-plus mr-2"></i> Create New Table
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:gap-8 px-4 sm:px-0">
        @foreach($tables as $table)
            <div class="bg-white border border-gray-300 rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl sm:text-2xl font-medium text-gray-800">{{ ucfirst($table->name) }}</h2>
                </div>
                <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 mt-4">
                    <a href="{{ route('table.index', $table->name) }}" class="flex-1 sm:flex-initial flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-cogs mr-2"></i> Manage
                    </a>

                    <a href="{{ route('crud.edit', $table->id) }}" class="flex-1 sm:flex-initial flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-700 transition duration-300">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>

                    <button type="button" onclick="confirmDelete({{ $table->id }})" class="flex-1 sm:flex-initial flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Table Data Visualization Section -->
<div class="bg-white p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Table Data Visualization</h2>
    <canvas id="tableChart" width="100%" height="30"></canvas>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Penghapusan</h3>
            <p class="text-gray-600 mb-8">Apakah Anda yakin ingin menghapus tabel ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center space-x-4">
                <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-300">
                    Batal
                </button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
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

    // Modal confirmation functions
    function confirmDelete(tableId) {
        const modal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        // Set the form action with the table ID
        deleteForm.action = "{{ route('crud.destroy', '') }}/" + tableId;

        // Show the modal
        modal.classList.remove('hidden');
    }

    // Cancel button event
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
    });

    // Close modal if user clicks outside of it
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
</script>
@endsection
