<?php

namespace App\Admin\Controllers;

use App\Models\Sample;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class SampleController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Sample(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->sample_num;
            $grid->sample_name;
            $grid->device_id;
            $grid->unit_name;
            $grid->site_name;
            $grid->receive_at;
            $grid->weather;
            $grid->created_at;
            $grid->updated_at->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('sample_num')->width(2);
                $filter->like('sample_name')->width(2);

            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Sample(), function (Form $form) {
            $form->display('id');
            $form->text('sample_num');
            $form->text('sample_name');
            $form->multipleSelect('form2.multiple-select', 'multiple select')->options();
            $form->text('device_id');
            $form->text('unit_name');
            $form->text('site_name');
            $form->text('receive_at');
            $form->text('weather');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
