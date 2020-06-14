<?php

namespace App\Admin\Controllers;

use App\Models\Project;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class ProjectController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Project(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->name;
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
        return Form::make(new Project(), function (Form $form) {
            $form->display('id');
            $form->text('name');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
