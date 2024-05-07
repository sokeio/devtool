<?php

namespace Sokeio\Devtool\Livewire\Crud;

use Illuminate\Support\Facades\Schema;
use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Devtool\GenerateCrud;
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
    public function generateCrud()
    {
        $this->skipClose = true;
        $this->doSave();
        GenerateCrud::generate($this->dataId);
        $this->showMessage(__('Crud generated successfully.'));
    }
    protected function footerUI()
    {
        return [
            UI::Div([
                UI::button(__('Save'))->wireClick('doSave()'),
                UI::button(__('Generate Crud'))->success()->wireClick('generateCrud()'),
                UI::button(__('Download Json'))->warning()->xClick('downloadJson()'),
                UI::button(__('Import Json'))->danger()->xClick('importJson()'),

            ])->className('p-2 text-center')
                ->xData("{
                    importJson() {
                        let element = document.createElement('input');
                        element.setAttribute('type', 'file');
                        element.click();
                        element.onchange = () => {
                            
                            let file = element.files[0];
                            let reader = new FileReader();
                            reader.readAsText(file, 'UTF-8');
                            reader.onload = event => {
                                let data = JSON.parse(event.target.result);
                                \$wire.data = data;
                            }
                        }
                    },
                    downloadJson() {
                        let text = JSON.stringify(\$wire.data);
                        let filename = 'crud_'+\$wire.data.name+'_' + new Date().getTime() + '.json';
                        let element = document.createElement('a');
                        element.setAttribute('href', 'data:application/json;charset=utf-8,' + encodeURIComponent(text));
                        element.setAttribute('download', filename);
                        element.click();
                    }
            }")
        ];
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
                    })->col2(),
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
                    ])->col4(),
                    UI::text('table_name')->label(__('Table Name'))->col3()
                        ->attributeInput('
                        x-bind:disabled="!($wire.data.model===\'\'|| $wire.data.model===undefined)"
                        ')
                        ->afterUI([
                            UI::button(__('Generate Model'))->wireClick('generateModel()')
                                ->attribute(' x-show="$wire.data.model===\'\'|| $wire.data.model===undefined" ')
                        ])->valueDefault(''),
                        UI::text('route')->label(__('Route'))->col3(),
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
                        ->label(__('Table UI'))
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
