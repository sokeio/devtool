<?php

namespace Sokeio\Devtool;

use Illuminate\Support\Facades\File;
use Sokeio\Devtool\Models\Crud;
use Sokeio\Facades\Module;

class GenerateCrud
{
    private $crud;
    private $path = '';
    private $module;
    public function __construct(private $crudId)
    {
        $this->crud = Crud::find($this->crudId);
        $this->module = Module::find($this->crud->module);
        $this->path = $this->module->getPath('src/Livewire')
            . '/' . ucfirst($this->crud->name);
    }
    public static function generate($crudId)
    {
        return (new self($crudId))->run();
    }
    public function run()
    {
        if (!File::exists($this->path)) {
            File::makeDirectory($this->path, 0775, true);
        }
        $this->generateForm();
        $this->generateTable();
        $this->generateRoute();
    }
    private function getData()
    {
        $models = explode('\\', $this->crud->model);
        $routes = explode('.', $this->crud->route);
        $route_prefix = '';
        for ($i = 0; $i < count($routes) - 1; $i++) {
            $route_prefix .= $routes[$i] . '.';
        }

        return [
            '##$namespace$##' =>  $this->module->getNamespaceInfo(),
            '##$name$##' => $this->crud->name,
            '##$model$##' => $this->crud->model,
            '##$model_name$##' =>  $models[count($models) - 1],
            '##$route$##' => $this->crud->route,
            '##$route_name$##' => $routes[count($routes) - 1],
            '##$route_prefix$##' =>  $route_prefix,
            '##$prefix_name$##' => str($this->module->getName())->lower(),
        ];
    }
    private function generateRoute()
    {
        $content = file_get_contents(__DIR__ . '/../stubs/route.stub');
        $data = $this->getData();
        foreach ($data as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        file_put_contents($this->module->getPath('routes/admin/') . str($this->crud->name)->lower() . '.php', $content);
    }
    private function getFormUI()
    {
        $html = '';
        foreach ($this->crud->form as $field) {
            $html .= 'UI::' . $field['uiType'] . '("' . $field['name'] . '")->label(__("' . $field['title'] . '"))->col6(),' . "\n\t\t\t\t\t\t";
        }
        return trim(trim($html), ',');
    }
    public function generateForm()
    {
        $content = file_get_contents(__DIR__ . '/../stubs/form.stub');
        $data = $this->getData();
        foreach ($data as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        $content = str_replace('##$formUI$##', $this->getFormUI(), $content);
        file_put_contents($this->path . '/' . ucfirst($this->crud->name) . 'Form.php', trim($content));
    }
    private function getColumnUI()
    {
        $html = '';
        foreach ($this->crud->table as $field) {
            $html .= 'UI::' . $field['uiType'] . '("' . $field['name'] . '")->label(__("' . $field['title'] . '")),' . "\n\t\t\t";
        }
        return trim(trim($html), ',');
    }
    public function generateTable()
    {
        $content = file_get_contents(__DIR__ . '/../stubs/table.stub');
        $data = $this->getData();
        foreach ($data as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        $content = str_replace('##$columns$##', $this->getColumnUI(), $content);
        file_put_contents($this->path . '/' . ucfirst($this->crud->name) . 'Table.php', trim($content));
    }
}
