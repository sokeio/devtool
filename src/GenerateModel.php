<?php

namespace Sokeio\Devtool;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Sokeio\Facades\Module;

class GenerateModel
{
    public static function generate($module, $table_name, $model_name, $fields, $is_migrate)
    {
        return (new self($module, $table_name, $model_name, $fields, $is_migrate))->run();
    }
    private $namespace;
    public function __construct(
        private $module,
        private $table_name,
        private $model_name,
        private $fields,
        private $is_migrate = false
    ) {
        $this->module = Module::find($this->module);
        $this->namespace = $this->module->getNamespaceInfo();
    }

    public function run()
    {
        if ($this->is_migrate) {
            $this->generateMigration();
        }
        $this->generateModel();
    }
    private function generateModel()
    {
        $content = file_get_contents(__DIR__ . '/../stubs/model.stub');
        $fillable = '';
        foreach ($this->fields as $field) {
            if (!empty($field['fillable'])) {
                $fillable .= '"' . $field['name'] . '",';
            }
        }
        $data = [
            '##$table_name$##' => $this->table_name,
            '##$model_name$##' => ucfirst($this->model_name),
            '##$namespace$##' => $this->namespace,
            // '##$fields$##' => $this->fields,
            '##$fillable$##' => trim($fillable, ','),
        ];
        foreach ($data as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        file_put_contents($this->module->getPath('src/Models/' . $this->model_name . '.php'), $content);
    }
    private $dbColumnTypes = [
        'varchar'    => [
            'name'     => 'string',
            'default'  => 255,
        ],
        'nvarchar'   => [
            'name'     => 'string',
            'default'  => 255,
        ],
        'text'       => [
            'name'     => 'text',
            'default'  => null,
        ],
        'int'        => [
            'name'     => 'integer',
            'default'  => null,
        ],
        'bigint'     => [
            'name'     => 'bigInteger',
            'default'  => null,
        ],
        'float'      => [
            'name'     => 'float',
            'default'  => null,
        ],
        'double'     => [
            'name'     => 'double',
            'default'  => null,
        ],
        'decimal'    => [
            'name'     => 'decimal',
            'default'  => '8,2',
        ],
        'date'       => [
            'name'     => 'date',
            'default'  => null,
        ],
        'datetime'   => [
            'name'     => 'dateTime',
            'default'  => null,
        ],
        'timestamp'  => [
            'name'     => 'timestamp',
            'default'  => null,
        ],
        'time'       => [
            'name'     => 'time',
            'default'  => null,
        ],
        'tinyint'    => [
            'name'     => 'tinyInteger',
            'default'  => null,
        ],
        'bit'        => [
            'name'     => 'boolean',
            'default'  => null,
        ],
        'json'       => [
            'name'     => 'json',
            'default'  => null,
        ],
        'enum'       => [
            'name'     => 'enum',
            'default'  => null,
        ],
        'mediumint'  => [
            'name'     => 'mediumInteger',
            'default'  => null,
        ],
        'smallint'   => [
            'name'     => 'smallInteger',
            'default'  => null,
        ],
        'binary'     => [
            'name'     => 'binary',
            'default'  => null,
        ],
        'longtext'   => [
            'name'     => 'longText',
            'default'  => null,
        ],
        'mediumtext' => [
            'name'     => 'mediumText',
            'default'  => null,
        ],
        'tinytext'   => [
            'name'     => 'tinyText',
            'default'  => null,
        ],
        'char'       => [
            'name'     => 'char',
            'default'  => null,
        ],
        'varbinary'  => [
            'name'     => 'binary',
            'default'  => null,
        ],
        'year'       => [
            'name'     => 'year',
            'default'  => null,
        ],
        'set'        => [
            'name'     => 'set',
            'default'  => null,
        ],
        'geometry'   => [
            'name'     => 'geometry',
            'default'  => null,
        ],
        'point'      => [
            'name'     => 'point',
            'default'  => null,
        ],
        'linestring' => [
            'name'     => 'lineString',
            'default'  => null,
        ],
        'polygon'    => [
            'name'     => 'polygon',
            'default'  => null,
        ],
        'multipoint' => [
            'name'     => 'multiPoint',
            'default'  => null,
        ],
        'multilinestring' => [
            'name'     => 'multiLineString',
            'default'  => null,
        ],
        'multipolygon'    => [
            'name'     => 'multiPolygon',
            'default'  => null,
        ],
        'geometrycollection' => [
            'name'     => 'geometryCollection',
            'default'  => null,
        ],
    ];
    private function generateField($field)
    {
        $fieldText = '';
        $dbType = $this->dbColumnTypes[$field['type']];
        $fieldText .= '        $table->' . $dbType['name'] . '("' . $field['name'] . '"';
        if ($field['length'] != null && $field['length'] != '' && $field['length'] != '0' && $field['length'] != $dbType['default']) {
            $fieldText .= ',' . $field['length'] . ')';
        } else {
            $fieldText .= ')';
        }
        if (!empty($field['comment'])) {
            $fieldText .= '->comment("' . $field['comment'] . '")';
        }
        if (!empty($field['default']) && $field['default'] !== '') {
            $fieldText .= '->default("' . $field['default'] . '")';
        }
        // if (!empty($field['collation'])&& $field['collation'] !== '') {
        //     $fieldText .= '->collation("' . $field['collation'] . '")';
        // }
        if (!empty($field['nullable']) && $field['nullable'] !== false) {
            $fieldText .= '->nullable()';
        }
        return $fieldText;
    }
    private function generateMigration()
    {
        $content = file_get_contents(__DIR__ . '/../stubs/migration.stub');
        $table_fields = '';
        foreach ($this->fields as $field) {
            if ($field['name'] == 'id' && $field['auto_increment']) {
                continue;
            }
            if (($field['name'] == 'created_at' || $field['name'] == 'updated_at') && $field['type'] == 'timestamp') {
                continue;
            }
            $table_fields .= $this->generateField($field) . ';' . PHP_EOL;
        }
        $data = [
            '##$table_name$##' => $this->table_name,
            '##$table_fields$##' => $table_fields
        ];
        foreach ($data as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
        $filename = Carbon::now()->format('Y_m_d_His')
            . '_' . str('create_' . $this->table_name . '_table')->lower() . '.php';
        file_put_contents($this->module->getPath('database/migrations/' . $filename), $content);
    }
}
