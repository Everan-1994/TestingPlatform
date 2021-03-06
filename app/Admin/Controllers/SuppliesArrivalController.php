<?php

namespace App\Admin\Controllers;

use App\Models\SuppliesArrival;
use App\Models\Supply;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Layout\Content;

class SuppliesArrivalController extends AdminController
{
    public function index(Content $content)
    {
        return $content->full()->body($this->grid());
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new SuppliesArrival(), function (Grid $grid) {
            // 带参数查询
            $grid->model()->where('supplies_id', '=', request()->query('id'));
            $grid->export([
                'id' => '序号',
                'stock' => '物资库存',
                'add_stock' => '到货数量',
                'created_at' => '记录时间'
            ])->filename('物资到货记录'); // 导出
            $grid->id->sortable();
            $grid->stock;
            $grid->add_stock;
            $grid->created_at->sortable();

            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchDelete();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->between('created_at', '到货时间')->datetime()->width(4);
            });
        });
    }

    /**
     * @return Form
     */
    public function form()
    {
        return Form::make(new SuppliesArrival(), function (Form $form) {
            $form->display('id');
            $form->text('add_stock')->required(true)->rules('integer|min:1', [
                'integer' => '必须为数字',
                'min'   => '必须为大于0的数量',
            ]);
            // 设置提交的action
            if ($form->isCreating()) {
                $supplies_id = request('supplies_id');
                $stock = request('stock');
                $form->action("/supplies_arrival?supplies_id=$supplies_id&stock=$stock");
            }

            $form->hidden('supplies_id');
            $form->hidden('stock');
        })->saving(function (Form $form) {
            if (request()->query('supplies_id')) {
                $form->supplies_id = request()->query('supplies_id');
            }
            if (request()->query('stock') >= 0) {
                $form->stock = request()->query('stock');
            }
        })->saved(function (Form $form) {
            $supply = Supply::query()->find(request()->query('supplies_id'));
            $supply->stock += $form->input('add_stock');
            $supply->save();
        });
    }
}
