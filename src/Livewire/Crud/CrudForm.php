<?php

namespace Sokeio\Devtool\Livewire\Crud;

use Illuminate\Support\Facades\Schema;
use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Devtool\Models\Crud;
use Sokeio\Facades\Module;
use Sokeio\Facades\Platform;

class CrudForm extends Form
{
    public $dbColumnTypes = [
        'varchar' => 'text',
        'nvarchar' => 'text',
        'text' => 'textarea',
        'int' => 'number',
        'bigint' => 'number',
        'float' => 'number',
        'double' => 'number',
        'decimal' => 'number',
        'date' => 'date',
        'datetime' => 'date',
        'timestamp' => 'date',
        'time' => 'time',
        'tinyint' => 'number',
        'bit' => 'checkbox',
        'json' => 'json',
        'enum' => 'select',
    ];
    public $optionFieldDefaults = [
        'col' => [
            'type' => 'select',
            'data' => [
                'col1',
                'col2',
                'col3',
                'col4',
                'col5',
                'col6',
                'col7',
                'col8',
                'col9',
                'col10',
                'col11',
                'col12'
            ],
            'default' => 'col6'
        ]
    ];
    public $uiFieldTypes = [
        'text' => [],
        'textarea' => [],
        'number' => [],
        'date' => [],
        'time' => [],
        'checkbox' => [],
        'select' => [],
        'json' => [],
        'hidden' => [],
        'file' => [],
        'image' => [],
    ];
    protected function getModel()
    {
        return Crud::class;
    }
    private function getLengthFromType($type)
    {
        $e = explode('(', $type);
        if (isset($e[1])) {
            return explode(')', trim($e[1], ')'))[0];
        }
        return null;
    }
    public function loadFields()
    {
        $this->skipRender();
        if ($this->data->model && $model = app($this->data->model)) {
            $this->data->table_name = $model->getTable();
            $columns = Schema::getColumns($this->data->table_name);
            $fillable = $model->getFillable();
            $this->data->fields = collect($columns)->map(function ($item) use ($fillable) {
                $type = $item['type'];
                return [
                    ...$item,
                    'fillable' => in_array($item['name'], $fillable),
                    'type' => trim($item['type_name']),
                    'length' => $this->getLengthFromType($type),
                ];
            });
        }
        $this->showMessage(__('Fields loaded successfully.'));
    }

    protected function formUI()
    {
        return UI::container([
            UI::prex('data', [
                UI::row([
                    UI::text('name')->label(__('Name'))->col12(),
                    UI::select('module')->label(__('Module'))->dataSource(function () {
                        return Module::getAll()->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'title' => $item->name
                            ];
                        });
                    })->col3(),
                    UI::select('model')->label(__('Model'))->dataSource(function () {
                        return [
                            [
                                'id' => '',
                                'title' => 'None'
                            ], ...collect(Platform::getModels())->map(function ($item, $key) {
                                return [
                                    'id' => $key,
                                    'title' => $item
                                ];
                            })->toArray()
                        ];
                    })->afterUI([
                        UI::button(__('Load Fields'))->wireClick('loadFields')
                    ])->col5(),
                    UI::text('route')->label(__('Route'))->col4(),
                    UI::text('table_name')->label(__('Table Name'))->col4()
                        ->attributeInput('
                        x-bind:disabled="!($wire.data.model===\'\'|| $wire.data.model===undefined)"
                        ')
                        ->afterUI([
                            UI::button(__('Generate Model'))->wireClick('generateModel()')
                                ->attribute(' x-show="$wire.data.model===\'\'|| $wire.data.model===undefined" ')
                        ])->valueDefault(''),
                    UI::templateField('fields')
                        ->label(__('Fields'))
                        ->templateView('devtool::crud.fields')
                        ->valueDefault(function () {
                            return [];
                        })
                        ->col12()
                        ->expanded(),
                    UI::templateField('form')
                        ->label(__('Form UI'))
                        ->templateView('devtool::crud.form')
                        ->valueDefault(function () {
                            return [];
                        })
                        ->col12()
                        ->expanded(),
                    UI::templateField('table')
                        ->label(__('Form UI'))
                        ->templateView('devtool::crud.table')
                        ->valueDefault(function () {
                            return [];
                        })
                        ->col12()
                        ->expanded(),
                ])
            ])
        ])->className('p-3');
    }
}
