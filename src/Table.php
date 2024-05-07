<?php
namespace Sokeio\Builder\Livewire\Tag;
use Sokeio\Components\Table;
use Sokeio\Components\UI;
use Sokeio\Blog\Models\Tag;

class TagTable extends Table
{
    protected function getTitle()
    {
        return __('Tag');
    }
    protected function getRoute()
    {
        return 'admin.builder.tag';
    }
    protected function getModel()
    {
        return Tag::class;
    }
    protected function getModalSize($isNew = true, $row = null)
    {
        return UI::MODAL_EXTRA_LARGE;
    }
    protected function getColumns()
    {
        return [
            UI::number("id")->label(__("id")),
			UI::text("title")->label(__("title")),
			UI::text("image")->label(__("image")),
			UI::text("view_layout")->label(__("view_layout")),
			UI::number("author_id")->label(__("author_id")),
			UI::text("description")->label(__("description")),
			UI::text("status")->label(__("status")),
			UI::date("created_at")->label(__("created_at")),
			UI::date("updated_at")->label(__("updated_at"))
        ];
    }
}
