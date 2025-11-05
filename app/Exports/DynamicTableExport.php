<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\CrudTable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DynamicTableExport implements FromCollection, WithHeadings
{
    protected $tableName;
    protected $columns;
    protected $search;

    public function __construct($tableName, $search = null)
    {
        $this->tableName = $tableName;
        $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
        $this->columns = $crudTable->columns;
        $this->search = $search;
    }

    public function collection()
    {
        $query = DB::table($this->tableName);

        foreach ($this->columns as $column) {
            if ($column->is_relation && $column->related_table_id) {
                $relatedTable = CrudTable::find($column->related_table_id);
                if ($relatedTable) {
                    $aliasName = "{$relatedTable->name}_relation";

                    $query->leftJoin(
                        $relatedTable->name . ' as ' . $aliasName,
                        "{$this->tableName}.{$column->name}",
                        '=',
                        "$aliasName.id"
                    );

                    $query->addSelect([
                        "$aliasName.name as {$column->name}_name"
                    ]);
                }
            }
        }

        $query->addSelect("{$this->tableName}.*");

        if ($this->search) {
            $query->where(function($q) {
                foreach ($this->columns as $column) {
                    if (!$column->is_relation) {
                        $q->orWhere("{$this->tableName}.{$column->name}", 'like', "%{$this->search}%");
                    } else {
                        $relatedTable = CrudTable::find($column->related_table_id);
                        if ($relatedTable) {
                            $aliasName = "{$relatedTable->name}_relation";
                            $q->orWhere("$aliasName.name", 'like', "%{$this->search}%");
                        }
                    }
                }
            });
        }

        $data = $query->get();

        return $data->map(function($row) {
            $mappedRow = [];
            foreach ($this->columns as $column) {
                if ($column->is_relation && $column->related_table_id) {
                    $mappedRow[] = $row->{$column->name . '_name'} ?? '-';
                } else {
                    $mappedRow[] = $row->{$column->name} ?? '-';
                }
            }
            return $mappedRow;
        });
    }

    public function headings(): array
    {
        return $this->columns->map(function($column) {
            // Jika kolom adalah relasi, hapus '_id' dan gunakan format yang lebih bersih
            if ($column->is_relation) {
                // Mengubah format seperti 'category_id' menjadi 'Category'
                return ucfirst(str_replace(['_id', '_name'], '', $column->name));
            }
            // Untuk kolom normal, gunakan nama asli dengan huruf kapital di awal
            return ucfirst($column->name);
        })->toArray();
    }
}
