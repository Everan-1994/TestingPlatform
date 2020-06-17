<?php

namespace App\Admin\Controllers;

use App\Models\Supply;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Layout\Content;

class SupplyController extends AdminController
{
    public function index(Content $content)
    {
        Admin::script($this->arrival());
        Admin::script($this->receive());

        return $content
            ->header('物资')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Supply(), function (Grid $grid) {
            // 设置行操作为排列模式
            // $grid->setActionClass(Grid\Displayers\Actions::class);
            $grid->export([
                'id' => '序号',
                'name' => '物资名称',
                'stock' => '物资库存',
                'created_at' => '创建时间'
            ])->filename('物资列表'); // 导出
            $grid->id->sortable();
            $grid->name->editable();
            $grid->stock->editable();
            $grid->created_at->sortable();

            $grid->actions(function ($actions) {
                $actions->disableEdit();
                $actions->disableView();
            });


            $grid->actions(function (Grid\Displayers\Actions $actions) {
                // 获取当前行主键值
                $id = $actions->getKey();
                $name = $actions->row->name;
                $q = '?supplies_id=' . $id . '&stock=' . $actions->row->stock;
                $add_receive_url = route('admin.supply.receive.create') . $q;
                $add_arrival_url = route('admin.supply.arrival.create') . $q;

                $actions->append('<a href="javascript:;" class="receive" data-id="' . $id . '" data-name="' . $name . '" ><i class="fa fa-paper-plane-o"></i> 领用记录</a>');
                $actions->append('<a href="javascript:;" class="dialog-create" data-url="' . $add_receive_url . '" ><i class="fa fa-plus-circle"></i> 添加领用</a>');
                $actions->append('<a href="javascript:;" class="arrival" data-id="' . $id . '" data-name="' . $name . '" ><i class="fa fa-ambulance"></i> 到货记录</a>');
                $actions->append('<a href="javascript:;" class="dialog-create" data-url="' . $add_arrival_url . '" ><i class="fa fa-plus-circle"></i> 添加到货</a>');
            });

            $grid->setDialogFormDimensions('600px', '470px');
            // $grid->showQuickEditButton();
            $grid->enableDialogCreate();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('name');
            });
        });
    }

    protected function receive()
    {
        $url = route('admin.supply.receive');
        return <<<JS
$('.receive').on('click', function () {
    var id = $(this).data('id')
    var name = $(this).data('name')
    layer.open({
      type: 2,
      title: name,
      shadeClose: true,
      shade: 0.8,
      area: ['90%', '80%'],
      content: "$url?id=" + id
    });
});
JS;
    }

    protected function arrival()
    {
        $url = route('admin.supply.arrival');
        return <<<JS
$('.arrival').on('click', function () {
    var id = $(this).data('id')
    var name = $(this).data('name')
    layer.open({
      type: 2,
      title: name,
      shadeClose: true,
      shade: 0.8,
      area: ['90%', '80%'],
      content: "$url?id=" + id
    });
});
JS;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Supply(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required(true);
            $form->text('stock')->required(true);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
