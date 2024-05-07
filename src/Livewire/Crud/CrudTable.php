<?php

namespace Sokeio\Devtool\Livewire\Crud;

use Sokeio\Components\Table;
use Sokeio\Components\UI;
use Sokeio\Devtool\Models\Crud;

class CrudTable extends Table
{
    protected function getTitle()
    {
        return __('CRUD');
    }
    protected function getRoute()
    {
        return 'admin.devtool.crud';
    }
    protected function getModel()
    {
        return Crud::class;
    }
    protected function getColumns()
    {
        return [
            UI::text('name')->label(__('Name')),
            UI::text('model')->label(__('Model')),
            UI::text('route')->label(__('Route')),
            UI::text('module')->label(__('Module')),
        ];
    }
}
