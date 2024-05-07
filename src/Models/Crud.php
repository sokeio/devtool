<?php

namespace Sokeio\Devtool\Models;

use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    protected $table = 'dev_crud';
    protected $fillable = [
        'user_id', 'module', 'name', 'model', 'route', 'fields', 'form', 'table', 'config'
    ];
    protected $guarded = [];
    protected $casts = [
        'config' => 'array',
        'fields' => 'array',
        'form' => 'array',
        'table' => 'array'
    ];
    public function Logs()
    {
        return $this->hasMany(CrudLog::class, 'dev_crud_id', 'id');
    }
}
