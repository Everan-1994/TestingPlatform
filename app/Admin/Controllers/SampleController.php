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
                $filter->equal('id');
        
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Sample(), function (Show $show) {
            $show->id;
            $show->sample_num;
            $show->sample_name;
            $show->device_id;
            $show->unit_name;
            $show->site_name;
            $show->receive_at;
            $show->weather;
            $show->created_at;
            $show->updated_at;
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
