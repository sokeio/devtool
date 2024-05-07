<?php

use Illuminate\Support\Facades\Route;
use Sokeio\Devtool\Livewire\Crud\CrudForm;
use Sokeio\Devtool\Livewire\Crud\CrudTable;

Route::group(['as' => 'admin.devtool.', 'prefix' => 'devtool'], function () {
    routeCrud('crud', CrudTable::class, CrudForm::class);
});
