<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrudTable extends Model
{
    protected $fillable = ['name'];

    public function columns()
    {
        return $this->hasMany(CrudColumn::class);
    }
}
