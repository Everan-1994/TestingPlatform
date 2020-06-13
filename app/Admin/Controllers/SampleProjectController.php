<?php

namespace App\Admin\Controllers;

use App\Models\SampleProject;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class SampleProjectController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new SampleProject(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->sample_id;
            $grid->project_id;
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
        return Show::make($id, new SampleProject(), function (Show $show) {
            $show->id;
            $show->sample_id;
            $show->project_id;
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
        return Form::make(new SampleProject(), function (Form $form) {
            $form->display('id');
            $form->text('sample_id');
            $form->text('project_id');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
