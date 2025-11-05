<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CrudTable;
use App\Models\CrudColumn;
use App\Models\ApiRoute;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class CrudBuilderController extends Controller
{
    public function index()
    {
        $tables = CrudTable::all();

        // Fetch the number of entries for each table
        $tableData = $tables->map(function($table) {
            $tableName = $table->name;
            $entryCount = DB::table($tableName)->count();

            return [
                'name' => ucfirst($tableName),
                'entries' => $entryCount,
            ];
        });

        return view('crud.index', compact('tables', 'tableData'));
    }

    public function show($table)
    {
        $tableName = $table;
        $tableModel = CrudTable::where('name', $tableName)->first();

        if (!$tableModel) {
            return redirect()->route('crud.index')->with('error', 'Table not found.');
        }

        $columns = CrudColumn::where('crud_table_id', $tableModel->id)->get();

        // Get API route information for this table
        $apiRoute = ApiRoute::where('crud_table_id', $tableModel->id)->first();

        return view('crud.show', compact('tableName', 'columns', 'apiRoute'));
    }

    public function create()
    {
        $existingTables = CrudTable::all();
        return view('crud.create', compact('existingTables'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|unique:crud_tables,name',
            'columns' => 'required|array',
            'columns.*.name' => 'required',
            'columns.*.type' => 'required',
            'columns.*.input_type' => 'required',
        ]);

        // Save table metadata
        $crudTable = CrudTable::create(['name' => $request->name]);
        $tableName = $request->name;

        // Create physical table in database
        Schema::create($tableName, function (Blueprint $table) use ($request, $crudTable) {
            $table->id();

            foreach ($request->columns as $col) {
                $isRelation = isset($col['is_relation']) && $col['is_relation'] == '1';
                $relatedTableId = $isRelation ? intval($col['related_table_id']) : null;

                if ($isRelation && $relatedTableId > 0) {
                    $relatedTable = CrudTable::find($relatedTableId);
                    if ($relatedTable) {
                        $table->unsignedBigInteger($col['name']);
                        $table->foreign($col['name'])
                              ->references('id')
                              ->on($relatedTable->name)
                              ->onDelete('cascade');
                    } else {
                        $table->string($col['name'])->nullable();
                    }
                } else {
                    switch ($col['type']) {
                        case 'integer':
                            $table->integer($col['name'])->nullable();
                            break;
                        case 'text':
                            $table->text($col['name'])->nullable();
                            break;
                        case 'boolean':
                            $table->boolean($col['name'])->nullable();
                            break;
                        case 'date':
                            $table->date($col['name'])->nullable();
                            break;
                        case 'datetime':
                            $table->datetime($col['name'])->nullable();
                            break;
                        case 'decimal':
                            $table->decimal($col['name'], 10, 2)->nullable();
                            break;
                        case 'string':
                        default:
                            $table->string($col['name'])->nullable();
                            break;
                    }
                }
            }

            $table->timestamps();
        });

        // Save column metadata
        foreach ($request->columns as $col) {
            $isRelation = isset($col['is_relation']) && $col['is_relation'] == '1';
            $relatedTableId = $isRelation ? intval($col['related_table_id']) : null;

            CrudColumn::create([
                'crud_table_id' => $crudTable->id,
                'name' => $col['name'],
                'type' => $col['type'],
                'input_type' => $col['input_type'],
                'is_relation' => $isRelation,
                'related_table_id' => $relatedTableId,
            ]);
        }

        // Generate and save API route information
        $apiToken = Str::random(64); // Generate a secure token for API authentication
        ApiRoute::create([
            'crud_table_id' => $crudTable->id,
            'endpoint' => '/api/v1/' . $tableName,
            'methods' => 'GET,POST,PUT,DELETE',
            'description' => 'RESTful API endpoints for ' . $tableName,
            'api_token' => $apiToken
        ]);

        return redirect()->route('crud.index')->with('success', 'Table and API routes created successfully.');
    }

    public function edit($id)
    {
        // Get table data by ID
        $table = CrudTable::findOrFail($id);
        $columns = $table->columns;
        $existingTables = CrudTable::all(); // For displaying related tables in relational columns

        // Get API route information
        $apiRoute = ApiRoute::where('crud_table_id', $id)->first();

        return view('crud.edit', compact('table', 'columns', 'existingTables', 'apiRoute'));
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'columns' => 'required|array',
            'columns.*.name' => 'required|string|max:255',
            'columns.*.type' => 'required|string',
            'columns.*.input_type' => 'required|string',
        ]);

        // Update table metadata
        $tableModel = CrudTable::findOrFail($id);
        $oldTableName = $tableModel->name;
        $newTableName = $request->input('name');
        $tableModel->name = $newTableName;
        $tableModel->save();

        // If table name changed, rename physical table
        if ($oldTableName !== $newTableName) {
            Schema::rename($oldTableName, $newTableName);

            // Update API route endpoint
            $apiRoute = ApiRoute::where('crud_table_id', $id)->first();
            if ($apiRoute) {
                $apiRoute->endpoint = '/api/v1/' . $newTableName;
                $apiRoute->save();
            }
        }

        // Get existing columns in physical table
        $existingColumns = Schema::getColumnListing($newTableName);

        // Get columns from database
        $existingDbColumns = $tableModel->columns->pluck('id', 'name')->toArray();
        $columnIdsToKeep = [];

        // Update existing columns and add new ones
        foreach ($request->input('columns') as $columnData) {
            // Check if column already exists in physical table
            if (!in_array($columnData['name'], $existingColumns)) {
                // Add new column to physical table
                Schema::table($newTableName, function (Blueprint $schema) use ($columnData) {
                    // Add column based on selected type
                    switch ($columnData['type']) {
                        case 'integer':
                            $schema->integer($columnData['name'])->nullable();
                            break;
                        case 'text':
                            $schema->text($columnData['name'])->nullable();
                            break;
                        case 'boolean':
                            $schema->boolean($columnData['name'])->nullable();
                            break;
                        case 'date':
                            $schema->date($columnData['name'])->nullable();
                            break;
                        case 'datetime':
                            $schema->dateTime($columnData['name'])->nullable();
                            break;
                        case 'decimal':
                            $schema->decimal($columnData['name'], 10, 2)->nullable();
                            break;
                        case 'string':
                        default:
                            $schema->string($columnData['name'])->nullable();
                            break;
                    }
                });
            }

            // Update or add column in metadata
            if (isset($existingDbColumns[$columnData['name']])) {
                // Update existing column
                $column = CrudColumn::find($existingDbColumns[$columnData['name']]);
                $columnIdsToKeep[] = $column->id;
            } else {
                // Create new column
                $column = new CrudColumn();
            }

            $column->crud_table_id = $tableModel->id;
            $column->name = $columnData['name'];
            $column->type = $columnData['type'];
            $column->input_type = $columnData['input_type'];
            $column->is_relation = $columnData['is_relation'] ?? 0;
            $column->related_table_id = $columnData['related_table_id'] ?? null;
            $column->save();

            if (!isset($existingDbColumns[$columnData['name']])) {
                $columnIdsToKeep[] = $column->id;
            }
        }

        // Delete columns that are no longer in request
        CrudColumn::where('crud_table_id', $tableModel->id)
            ->whereNotIn('id', $columnIdsToKeep)
            ->delete();

        // Regenerate API token if requested
        if ($request->has('regenerate_api_token')) {
            $apiRoute = ApiRoute::where('crud_table_id', $id)->first();
            if ($apiRoute) {
                $apiRoute->api_token = Str::random(64);
                $apiRoute->save();
            }
        }

        return redirect()->route('crud.index')->with('success', 'Table, columns, and API routes updated successfully');
    }

    public function destroy($id)
    {
        $table = CrudTable::findOrFail($id);

        // Delete physical table from database
        Schema::dropIfExists($table->name);

        // Delete metadata (columns and record in crud_tables)
        // API routes will be automatically deleted due to cascade delete
        $table->columns()->delete();
        $table->delete();

        return redirect()->route('crud.index')->with('success', 'Table and associated API routes deleted successfully');
    }
}
