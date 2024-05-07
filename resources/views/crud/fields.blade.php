<div x-data="{
    initField() {
            if (dataFields === undefined) { dataFields = []; }
        },
        addField() {
            dataFields.push({
                id: 'row-' + (new Date()).getTime(),
                name: '',
                type_name: '',
                type: '',
                collation: '',
                nullable: '',
                default: '',
                auto_increment: '',
                comment: ''
            });
        }
}" x-init="initField">
    <button x-on:click="addField" class="btn btn-primary btn-sm">Add Field</button>
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th style="width: 120px">Name </th>
                <th style="width: 120px">type_name </th>
                <th style="width: 120px">length</th>
                <th style="width: 80px">nullable</th>
                <th style="width: 80px">auto_increment</th>
                <th style="width: 120px">default</th>
                <th style="min-width: 120px">comment</th>
                <th></th>
            </tr>
            <template x-for="field in dataFields">
                <tr>
                    <td class="p-1">
                        <input class="form-control form-control-sm" x-model="field.name" />
                    </td>
                    <td class="p-1">
                        <select class="form-control form-control-sm" x-model="field.type_name">
                            <template x-for="type in Object.keys($wire.dbColumnTypes)">
                                <option x-bind:selected="field.type_name === type" x-bind:value="type"
                                    x-text="type"></option>
                            </template>
                        </select>
                    </td>
                    <td class="p-1">
                        <input class="form-control form-control-sm" x-model="field.length" />
                    </td>
                    <td class="text-center p-1">
                        <input type="checkbox" class="form-check-input" x-model="field.nullable" />
                    </td>
                    <td class="text-center p-1">
                        <input type="checkbox" class="form-check-input" x-model="field.auto_increment" />
                    </td>
                    <td class="p-1">
                        <input class="form-control form-control-sm" x-model="field.default" />
                    </td>
                    <td class="p-1">
                        <input class="form-control form-control-sm" x-model="field.comment" />
                    </td>
                    <td class="text-center p-1">
                        <button class="btn btn-danger btn-sm"
                            @click="dataFields.splice(dataFields.indexOf(field), 1)">X</button>
                    </td>
                </tr>
            </template>
        </table>
    </div>
</div>
