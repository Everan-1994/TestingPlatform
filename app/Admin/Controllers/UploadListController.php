<?php

namespace App\Admin\Controllers;

use App\Models\Report;
use App\Models\UploadList;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\IFrameGrid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Widgets\Box;

class UploadListController extends AdminController
{
    public function index(Content $content)
    {
        Admin::script($this->uploadListDetail());

        return $content->full()->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(UploadList::with('user'), function (Grid $grid) {

            $grid->model()->where('sample_num', '=', request()->query('sample_num'));

            $grid->id->sortable();
            $grid->column('user.name', '取样人员');
            $grid->ss_name->editable();
            $grid->created_at->sortable();

            $grid->disableCreateButton();
            $grid->disableFilter();
            $grid->disableEditButton();

            $grid->actions(function ($actions) {
                $id = $actions->row->id;

                $actions->prepend('<a href="javascript:;" class="upload_list_detail" data-url="' . route('admin.upload_list.show', ['id' => $id]) . '" ><i class="fa fa-paper-plane-o"></i> 详情</a>');

                $actions->disableView();
            });
        });
    }

    protected function uploadListDetail()
    {

        return <<<JS
$('.upload_list_detail').on('click', function () {
    var url = $(this).data('url')
    layer.open({
      type: 2,
      title: '报告详情',
      shadeClose: true,
      shade: 0.8,
      area: ['94%', '95%'],
      content: url
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
        return Form::make(new UploadList(), function (Form $form) {
            $form->display('id');
            $form->text('ss_name');
        });
    }

    public function show($id, Content $content)
    {
        return $content->full()
            ->body(Box::make('基础信息', '内容'))
            ->body(Box::make('样本信息', IFrameGrid::make(Report::with(['device', 'project']), function (Grid $grid) use ($id) {
                $grid->model()->where('upload_list_id', '=', $id);
                $grid->column('id', '序号');
                $grid->column('project.name', '项目');
                $grid->column('device.name', '设备仪器');
                $grid->column('content', '样本检测报告')->limit(30, '...');
                $grid->column('created_at', '操作时间');
            })));
    }
}
