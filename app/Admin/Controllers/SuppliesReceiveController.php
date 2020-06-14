<?php

namespace App\Admin\Controllers;

use App\Models\SuppliesReceive;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Layout\Content;

class SuppliesReceiveController extends AdminController
{
    public function index(Content $content)
    {
        return $content->full()->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new SuppliesReceive(), function (Grid $grid) {
            // 带参数查询
            $grid->model()->where('supplies_id', '=', request()->query('id'));

            $grid->id->sortable();
            $grid->stock;
            $grid->sub_stock;
            $grid->created_at->sortable();

            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchDelete();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->between('created_at', '领用时间')->datetime()->width(4);
            });
        });
    }

    /**
     * @return mixed
     */
    public function form()
    {
        return Form::make(new SuppliesReceive(), function (Form $form) {
            $form->display('id');
            $form->text('sub_stock')->required(true);
            // 设置提交的action
            if ($form->isCreating()) {
                $supplies_id = request('supplies_id');
                $stock = request('stock');
                $form->action("/supplies_receive?supplies_id=$supplies_id&stock=$stock");
            }

            $form->hidden('supplies_id');
            $form->hidden('stock');
        })->saving(function (Form $form) {
            if (request()->query('supplies_id')) {
                $form->supplies_id = request()->query('supplies_id');
            }
            if (request()->query('stock')) {
                $form->stock = request()->query('stock');
            }
        });
    }
}
