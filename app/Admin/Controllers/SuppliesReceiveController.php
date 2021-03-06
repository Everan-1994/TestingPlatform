<?php

namespace App\Admin\Controllers;

use App\Models\SuppliesReceive;
use App\Models\Supply;
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
            $grid->export([
                'id' => '序号',
                'stock' => '物资库存',
                'sub_stock' => '领用数量',
                'created_at' => '记录时间'
            ])->filename('物资领用记录'); // 导出
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
            $form->text('sub_stock')->required(true)->rules('integer|min:1', [
                'integer' => '必须为数字',
                'min'   => '必须为大于1的数量',
            ]);
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

            $stock = Supply::query()->where('id', '=', request()->query('supplies_id'))->value('stock');

            if ($stock == 0 || $stock < $form->input('sub_stock')) {
                return $form->error('库存不足');
            }
        })->saved(function (Form $form) {
            $supply = Supply::query()->find(request()->query('supplies_id'));
            $supply->stock -= $form->input('sub_stock');
            $supply->save();
        });
    }
}
