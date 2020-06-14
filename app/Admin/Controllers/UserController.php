<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class UserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new User(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->name;
            $grid->username;
            $grid->employee_id;
            $grid->sex->using(User::$sexs)->dot([
                1 => 'success',
                2 => 'danger',
            ]);
            $grid->status->switch();
            $grid->created_at->sortable();

            $grid->actions(function ($actions) {
                $actions->disableEdit();
                $actions->disableView();
            });

            $grid->showQuickEditButton();
            $grid->enableDialogCreate();

            $grid->tools(function ($tools) {
                // 禁用批量删除按钮
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('name', '用户名')->width(2);
                $filter->like('username', '账号')->width(2);
                $filter->equal('sex', '性别')->select(User::$sexs)->width(2);
                $filter->between('created_at', '添加时间')->datetime()->width(4);
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
        return Form::make(new User(), function (Form $form) {
            $id = $form->getKey();
            $form->display('id');
            $form->text('name')->required();
            $form->text('username')
                ->required()
                ->creationRules(['required', "unique:users"],
                    [
                    'required' => '账号信息不能为空',
                    'unique' => '该账号已存在'
                    ])
                ->updateRules(['required', "unique:users,username,$id"], [
                    'required' => '账号信息不能为空',
                    'unique' => '该账号已存在']);
            if ($id) {
                $form->password('password')
                    ->minLength(6, '密码最少6位字符')
                    ->maxLength(10, '密码最大10位字符')
                    ->customFormat(function () {
                        return '';
                    });
            } else {
                $form->password('password')
                    ->required()
                    ->minLength(6, '密码最少6位字符')
                    ->maxLength(10, '密码最大10位字符');
            }
            $form->text('employee_id')->required()
                ->creationRules(['required', "unique:users"],
                    [
                        'required' => '工号信息不能为空',
                        'unique' => '该工号已存在'
                    ])
                ->updateRules(['required', "unique:users,employee_id,$id"], ['工号信息不能为空', '该工号已存在']);
            $form->radio('sex')->options(User::$sexs)->default(User::MALE);
            $form->switch('status', '状态')->saving(function ($v) {
                return $v ? User::STATUS_1 : User::STATUS_0;
            })->default(User::STATUS_1);

            $form->hidden('email');

            $form->display('created_at');
            $form->display('updated_at');

        })->saving(function (Form $form) {
            if ($form->password && $form->model()->get('password') != $form->password) {
                $form->password = bcrypt($form->password);
            }

            if (!$form->password) {
                $form->deleteInput('password');
            }

            if ($form->username && $form->model()->get('username') != $form->username) {
                $form->email = $form->input('username') . '@qq.com';
            }

            if ($form->isCreating()) {
                $form->email = $form->input('username') . '@qq.com';
            }
        });
    }
}
