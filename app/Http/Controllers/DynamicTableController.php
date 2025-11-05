<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CrudTable;
use App\Models\CrudColumn;
use Maatwebsite\Excel\Facades\Excel; // Pastikan import ini ada
use App\Exports\DynamicTableExport;


class DynamicTableController extends Controller
{
    public function index(Request $request, $tableName)
    {
        // Ambil data meta untuk table tertentu
        $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
        $columns = $crudTable->columns;

        // Ambil data dari table fisik
        $query = DB::table($tableName);

        // Tambahkan join untuk kolom yang merupakan relasi
        foreach ($columns as $column) {
            if ($column->is_relation && $column->related_table_id) {
                $relatedTable = CrudTable::find($column->related_table_id);
                if ($relatedTable) {
                    // Tambahkan alias unik untuk setiap join untuk menghindari konflik nama kolom
                    $aliasName = "{$relatedTable->name}_relation";

                    $query->leftJoin(
                        $relatedTable->name . ' as ' . $aliasName,
                        "$tableName.$column->name",
                        '=',
                        "$aliasName.id"
                    );

                    // Select nama dari tabel terkait dengan alias yang jelas
                    $query->addSelect([
                        "$tableName.*",
                        "$aliasName.name as {$column->name}_name"
                    ]);
                }
            }
        }

        // Filter: terapkan pencarian jika parameter 'search' ada
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search, $columns, $tableName) {
                foreach ($columns as $column) {
                    if (!$column->is_relation) {
                        $q->orWhere("$tableName.{$column->name}", 'like', "%{$search}%");
                    } else {
                        $relatedTable = CrudTable::find($column->related_table_id);
                        if ($relatedTable) {
                            $aliasName = "{$relatedTable->name}_relation";
                            $q->orWhere("$aliasName.name", 'like', "%{$search}%");
                        }
                    }
                }
            });
        }

        // Gunakan paginasi
        $data = $query->paginate(5);

        // Ambil daftar table
        $tables = CrudTable::all();

        // Siapkan data terkait untuk form
        $relatedTableData = [];
        foreach ($columns as $column) {
            if ($column->is_relation && $column->related_table_id) {
                $relatedTable = CrudTable::find($column->related_table_id);
                if ($relatedTable) {
                    $relatedTableData[$column->name] = DB::table($relatedTable->name)
                        ->select('id', 'name')
                        ->get();
                }
            }
        }

        return view('dynamic.index', compact(
            'tableName',
            'columns',
            'data',
            'tables',
            'relatedTableData'
        ));
    }

    public function exportExcel(Request $request, $tableName)
    {
        $search = $request->input('search');
        return Excel::download(new DynamicTableExport($tableName, $search), $tableName . '_data.xlsx');
    }

    public function store(Request $request, $tableName)
    {
        // Ambil meta data table dan kolomnya
        $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
        $columns = $crudTable->columns;

        $data = [];
        // Ambil input untuk setiap kolom
        foreach ($columns as $column) {
            // Jika kolom adalah relasi, pastikan nilai yang disimpan valid
            if ($column->is_relation && $column->related_table_id) {
                $inputValue = $request->input($column->name);
                if (!empty($inputValue)) {
                    $relatedTable = CrudTable::find($column->related_table_id);
                    // Periksa apakah ID yang dipilih ada di tabel terkait
                    if ($relatedTable) {
                        $exists = DB::table($relatedTable->name)
                                    ->where('id', $inputValue)
                                    ->exists();

                        if ($exists) {
                            $data[$column->name] = $inputValue;
                        }
                    }
                }
            } else {
                // Kolom biasa
                $data[$column->name] = $request->input($column->name);
            }
        }

        // Insert data ke table fisik
        DB::table($tableName)->insert($data);

        return redirect()->route('table.index', $tableName)
                         ->with('success', 'Data added successfully.');
    }

    // Method untuk menampilkan form edit
    public function edit($tableName, $id)
    {
        $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
        $columns = $crudTable->columns;
        $row = DB::table($tableName)->where('id', $id)->firstOrFail();

        // Ambil data dari tabel terkait untuk dropdown (jika ada)
        $relatedTableData = [];
        foreach ($columns as $column) {
            if ($column->is_relation && $column->related_table_id) {
                $relatedTable = CrudTable::find($column->related_table_id);
                if ($relatedTable) {
                    $relatedTableData[$column->name] = DB::table($relatedTable->name)->get();
                }
            }
        }

        return view('dynamic.edit', compact('tableName', 'columns', 'row', 'relatedTableData'));
    }

    // Method untuk meng-update data
    public function update(Request $request, $tableName, $id)
    {
        $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
        $columns = $crudTable->columns;

        $data = [];
        // Ambil input untuk setiap kolom
        foreach ($columns as $column) {
            if ($column->is_relation && $column->related_table_id) {
                $inputValue = $request->input($column->name);
                if (!empty($inputValue)) {
                    $relatedTable = CrudTable::find($column->related_table_id);
                    if ($relatedTable) {
                        $exists = DB::table($relatedTable->name)
                                    ->where('id', $inputValue)
                                    ->exists();
                        if ($exists) {
                            $data[$column->name] = $inputValue;
                        }
                    }
                }
            } else {
                $data[$column->name] = $request->input($column->name);
            }
        }

        // Update data di table fisik
        DB::table($tableName)->where('id', $id)->update($data);

        return redirect()->route('table.index', $tableName)
                         ->with('success', 'Data updated successfully.');
    }

    // Method untuk menghapus data
    public function destroy($tableName, $id)
    {
        DB::table($tableName)->where('id', $id)->delete();
        return redirect()->route('table.index', $tableName)
                         ->with('success', 'Data deleted successfully.');
    }
}
