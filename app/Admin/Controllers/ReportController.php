<?php

namespace App\Admin\Controllers;

use App\Models\Report;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class ReportController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Report(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->user_id;
            $grid->upload_list_id;
            $grid->project_id;
            $grid->device_id;
            $grid->content;
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
        return Show::make($id, new Report(), function (Show $show) {
            $show->id;
            $show->user_id;
            $show->upload_list_id;
            $show->project_id;
            $show->device_id;
            $show->content;
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
        return Form::make(new Report(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('upload_list_id');
            $form->text('project_id');
            $form->text('device_id');
            $form->text('content');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
