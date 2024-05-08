<?php

namespace Sokeio\Devtool\Models;

use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    protected $table = 'dev_crud';
    protected $fillable = [
        'user_id', 'module', 'name', 'model', 'route', 'table_name', 'fields', 'form', 'table', 'config'
    ];
    protected $guarded = [];
    protected $casts = [
        'config' => 'array',
        'fields' => 'array',
        'form' => 'array',
        'table' => 'array'
    ];
    protected static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->Logs()->create([
                ...$model->toArray(),
                'user_id' => auth()->user()?->id,
            ]);
        });
        self::updated(function ($model) {
            $model->Logs()->create([
                ...$model->toArray(),
                'user_id' => auth()->user()?->id,
            ]);
        });
        self::saved(function ($model) {
            $model->Logs()->create([
                ...$model->toArray(),
                'user_id' => auth()->user()?->id,
            ]);
        });
    }
    public function Logs()
    {
        return $this->hasMany(CrudLog::class, 'dev_crud_id', 'id');
    }
}
