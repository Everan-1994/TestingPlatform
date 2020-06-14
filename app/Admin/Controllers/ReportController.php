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
