<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrudColumn extends Model
{
    protected $fillable = [
        'crud_table_id',
        'name',
        'type',
        'input_type',
        'is_relation',
        'related_table_id'
    ];
    public function table()
    {
        return $this->belongsTo(CrudTable::class, 'crud_table_id');
    }

    public function relatedTable()
    {
        return $this->belongsTo(CrudTable::class, 'related_table_id');
    }
}
