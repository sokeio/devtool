<?php

namespace Sokeio\Builder\Livewire\Tag;

use Sokeio\Components\Form;
use Sokeio\Components\UI;
use Sokeio\Breadcrumb;
use Sokeio\Blog\Models\Tag;

class TagForm extends Form
{
    public function getTitle()
    {
        return __('Tag');
    }
    public function getBreadcrumb()
    {
        return [
            Breadcrumb::Item(__('Home'), route('admin.dashboard'))
        ];
    }
    public function getButtons()
    {
        return [];
    }
    public function getModel()
    {
        return Tag::class;
    }

    public function formUI()
    {
        return UI::container([
            UI::prex(
                'data',
                [
                    UI::row([
                        UI::number("id")->label(__("id"))->col6(),
						UI::text("title")->label(__("title"))->col6(),
						UI::text("image")->label(__("image"))->col6(),
						UI::text("view_layout")->label(__("view_layout"))->col6(),
						UI::number("author_id")->label(__("author_id"))->col6(),
						UI::text("description")->label(__("description"))->col6(),
						UI::text("status")->label(__("status"))->col6(),
						UI::date("created_at")->label(__("created_at"))->col6(),
						UI::date("updated_at")->label(__("updated_at"))->col6()
                    ])
                ]
            ),
        ])

            ->className('p-3');
    }
}
