<div x-data="{
    initField() {
            if (dataFields === undefined) { dataFields = []; }
        },
        addField() {
            dataFields.push({
                id: 'row-' + (new Date()).getTime(),
                name: '',
                title: '',
                uiType: '',
                options: []
            });
        },
        addUIFromFields() {
            dataFields = $wire.data.fields.map(field => {
                return {
                    id: 'row-' + (new Date()).getTime(),
                    name: field.name,
                    title: field.name,
                    uiType: $wire.dbColumnTypes[field.type_name],
                    options: []
                }
            });
        }
}" x-init="initField">
    <button x-on:click="addField" class="btn btn-primary btn-sm  mb-2 p-">Add Field</button>
    <button x-on:click="addUIFromFields" class="btn btn-primary btn-sm  mb-2 p-">Add UI from Fields</button>
    <div class="row" wire:sortable x-data="{
        onSortable(items) {
            console.log('sortable');
            console.log(items);
        }
    }">
        <template x-for="field in dataFields">
            <div class="col-6" wire:sortable.item :data-sortable-id="field.id">
                <div class="card mb-2 p-1 position-relative">
                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0"
                        @click="dataFields.splice(dataFields.indexOf(field), 1)">X</button>
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="fieldTitle" class="form-label">Title</label>
                                <input type="text" class="form-control form-control-sm" id="fieldTitle"
                                    x-model="field.title">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="fieldName" class="form-label">Name</label>
                                <select class="form-control form-control-sm" x-model="field.name" id="fieldName">
                                    <template x-for="type in $wire.data.fields">
                                        <option x-bind:selected="field.name === type.name" x-bind:value="type.name"
                                            x-text="type.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="fieldUIType" class="form-label">UI Type</label>
                                <select class="form-control form-control-sm" x-model="field.name" id="fieldUIType">
                                    <template x-for="type in Object.keys($wire.uiFieldTypes)">
                                        <option x-bind:selected="field.uiType === type" x-bind:value="type"
                                            x-text="type"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
