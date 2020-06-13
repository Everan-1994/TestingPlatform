<?php

namespace App\Admin\Controllers;

use App\Models\UploadList;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class UploadListController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new UploadList(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->user_id;
            $grid->ss_name;
            $grid->sample_id;
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
        return Show::make($id, new UploadList(), function (Show $show) {
            $show->id;
            $show->user_id;
            $show->ss_name;
            $show->sample_id;
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
        return Form::make(new UploadList(), function (Form $form) {
            $form->display('id');
            $form->text('user_id');
            $form->text('ss_name');
            $form->text('sample_id');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
