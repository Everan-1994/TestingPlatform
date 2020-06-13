<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class DeviceController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Device(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->name;
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
        return Show::make($id, new Device(), function (Show $show) {
            $show->id;
            $show->name;
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
        return Form::make(new Device(), function (Form $form) {
            $form->display('id');
            $form->text('name');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
