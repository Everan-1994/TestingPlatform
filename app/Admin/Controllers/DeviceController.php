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
            $grid->column('device_num')->display(function ($device_num) {
                return empty($device_num) ? '--' : $device_num;
            });
            $grid->created_at->sortable();

            $grid->actions(function ($actions) {
                $actions->disableEdit();
                $actions->disableView();
            });

            $grid->setDialogFormDimensions('600px', '450px');
            $grid->showQuickEditButton();
            $grid->enableDialogCreate();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('name')->width(3);
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
        return Form::make(new Device(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('device_num');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
