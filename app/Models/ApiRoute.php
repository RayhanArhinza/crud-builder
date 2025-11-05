<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'crud_table_id',
        'endpoint',
        'methods',
        'description',
        'api_token'
    ];

    /**
     * Get the table associated with this API route
     */
    public function crudTable()
    {
        return $this->belongsTo(CrudTable::class);
    }
}
