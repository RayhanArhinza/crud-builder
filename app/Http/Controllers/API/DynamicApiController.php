<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CrudTable;
use App\Models\CrudColumn;
use Illuminate\Support\Facades\Validator;

class DynamicApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $tableName
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($tableName)
    {
        try {
            // Verify table exists
            $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
            $columns = $crudTable->columns;

            // Build query with relations
            $query = DB::table($tableName);

            // Add joins for relations
            foreach ($columns as $column) {
                if ($column->is_relation && $column->related_table_id) {
                    $relatedTable = CrudTable::find($column->related_table_id);
                    if ($relatedTable) {
                        $aliasName = "{$relatedTable->name}_relation";

                        $query->leftJoin(
                            $relatedTable->name . ' as ' . $aliasName,
                            "$tableName.$column->name",
                            '=',
                            "$aliasName.id"
                        );

                        // Select with clear aliases
                        $query->addSelect([
                            "$tableName.*",
                            "$aliasName.name as {$column->name}_name"
                        ]);
                    }
                }
            }

            $data = $query->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found or error retrieving data',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $tableName
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $tableName)
    {
        try {
            // Verify table exists
            $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
            $columns = $crudTable->columns;

            // Prepare validation rules
            $rules = [];
            foreach ($columns as $column) {
                // Simple validation based on column type
                switch ($column->type) {
                    case 'integer':
                        $rules[$column->name] = 'nullable|integer';
                        break;
                    case 'decimal':
                        $rules[$column->name] = 'nullable|numeric';
                        break;
                    case 'date':
                        $rules[$column->name] = 'nullable|date';
                        break;
                    case 'datetime':
                        $rules[$column->name] = 'nullable|date';
                        break;
                    case 'boolean':
                        $rules[$column->name] = 'nullable|boolean';
                        break;
                    default:
                        $rules[$column->name] = 'nullable|string';
                }

                // Add relation validation if needed
                if ($column->is_relation && $column->related_table_id) {
                    $relatedTable = CrudTable::find($column->related_table_id);
                    if ($relatedTable) {
                        $rules[$column->name] = 'nullable|exists:'.$relatedTable->name.',id';
                    }
                }
            }

            // Validate request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data for insertion
            $data = [];
            foreach ($columns as $column) {
                $data[$column->name] = $request->input($column->name);
            }

            // Insert data
            $id = DB::table($tableName)->insertGetId($data);

            // Get the newly created record
            $newRecord = DB::table($tableName)->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Data created successfully',
                'data' => $newRecord
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $tableName
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($tableName, $id)
    {
        try {
            // Verify table exists
            $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
            $columns = $crudTable->columns;

            // Build query with relations
            $query = DB::table($tableName);

            // Add joins for relations
            foreach ($columns as $column) {
                if ($column->is_relation && $column->related_table_id) {
                    $relatedTable = CrudTable::find($column->related_table_id);
                    if ($relatedTable) {
                        $aliasName = "{$relatedTable->name}_relation";

                        $query->leftJoin(
                            $relatedTable->name . ' as ' . $aliasName,
                            "$tableName.$column->name",
                            '=',
                            "$aliasName.id"
                        );

                        // Select with clear aliases
                        $query->addSelect([
                            "$tableName.*",
                            "$aliasName.name as {$column->name}_name"
                        ]);
                    }
                }
            }

            $record = $query->where("$tableName.id", $id)->first();

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $record
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found or error retrieving data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $tableName
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $tableName, $id)
    {
        try {
            // Verify table exists
            $crudTable = CrudTable::where('name', $tableName)->firstOrFail();
            $columns = $crudTable->columns;

            // Check if record exists
            $record = DB::table($tableName)->where('id', $id)->first();
            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found'
                ], 404);
            }

            // Prepare validation rules
            $rules = [];
            foreach ($columns as $column) {
                // Simple validation based on column type
                switch ($column->type) {
                    case 'integer':
                        $rules[$column->name] = 'nullable|integer';
                        break;
                    case 'decimal':
                        $rules[$column->name] = 'nullable|numeric';
                        break;
                    case 'date':
                        $rules[$column->name] = 'nullable|date';
                        break;
                    case 'datetime':
                        $rules[$column->name] = 'nullable|date';
                        break;
                    case 'boolean':
                        $rules[$column->name] = 'nullable|boolean';
                        break;
                    default:
                        $rules[$column->name] = 'nullable|string';
                }

                // Add relation validation if needed
                if ($column->is_relation && $column->related_table_id) {
                    $relatedTable = CrudTable::find($column->related_table_id);
                    if ($relatedTable) {
                        $rules[$column->name] = 'nullable|exists:'.$relatedTable->name.',id';
                    }
                }
            }

            // Validate request data
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data for update
            $data = [];
            foreach ($columns as $column) {
                if ($request->has($column->name)) {
                    $data[$column->name] = $request->input($column->name);
                }
            }

            // Update data
            DB::table($tableName)->where('id', $id)->update($data);

            // Get the updated record
            $updatedRecord = DB::table($tableName)->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully',
                'data' => $updatedRecord
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $tableName
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($tableName, $id)
    {
        try {
            // Verify table exists
            $crudTable = CrudTable::where('name', $tableName)->firstOrFail();

            // Check if record exists
            $record = DB::table($tableName)->where('id', $id)->first();
            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found'
                ], 404);
            }

            // Delete record
            DB::table($tableName)->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
