<?php

namespace Sokeio\Devtool\Models;

use Illuminate\Database\Eloquent\Model;

class CrudLog extends Model
{
    protected $table = 'dev_crud_logs';
    protected $fillable = [
        'dev_crud_id',
        'user_id', 'module', 'name', 'model', 'route', 'fields', 'form', 'table', 'config'
    ];
    protected $guarded = [];
    protected $casts = [
        'config' => 'array',
        'fields' => 'array',
        'form' => 'array',
        'table' => 'array'
    ];
}
