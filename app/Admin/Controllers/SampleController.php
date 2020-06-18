<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\Project;
use App\Models\Sample;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use SimpleSoftwareIO\QrCode;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Controllers\AdminController;

class SampleController extends AdminController
{
    public function index(Content $content)
    {
        Admin::script($this->uploadList());
        Admin::script($this->qrcodePreview());

        return $content
            ->header('样品')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(Sample::with(['devices', 'projects']), function (Grid $grid) {
            $grid->id->sortable();
            $grid->sample_num;
            $grid->sample_name;
            $grid->projects()->pluck('name')->label();
            $grid->devices()->pluck('name')->label();
            $grid->unit_name;
            $grid->site_name;
            $grid->receive_at;
            $grid->weather;
            $grid->created_at->sortable();

            // $grid->column('qrcode', '二维码')->display(function () {
            //     return env('APP_URL') . $this->qrcode;
            // })->image('', 100, 100);

            $grid->column('qrcode', '二维码')->display(function () {
                $src = env('APP_URL') . $this->qrcode;
                return '<img src="'.$src.'" class="preview-qrcode" style="max-width: 100px;" data-sample-num="'.$this->sample_num.'" data-sample-name="'.$this->sample_name.'"/>';
            });

            $grid->actions(function ($actions) {
                $sample_num = $actions->row->sample_num;
                $name = $sample_num . '-' . $actions->row->sample_name;

                $actions->prepend('<a href="javascript:;" class="upload_list" data-id="' . $sample_num . '" data-name="' . $name . '" ><i class="fa fa-paper-plane-o"></i> 上传列表</a>');

                $actions->disableView();
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('sample_num')->width(2);
                $filter->like('sample_name')->width(2);
                $filter->equal('projects.project_id', '项目')->width(2)->select(Project::all()->pluck('name', 'id'));
                $filter->equal('devices.device_id', '设备')->width(2)->select(Device::all()->pluck('name', 'id'));
            });
        });
    }

    protected function uploadList()
    {
        $url = route('admin.upload_list.index');
        return <<<JS
$('.upload_list').on('click', function () {
    var sample_num = $(this).data('id')
    var name = $(this).data('name')
    layer.open({
      type: 2,
      title: name,
      shadeClose: true,
      shade: 0.8,
      area: ['90%', '80%'],
      content: "$url?sample_num=" + sample_num
    });
});
JS;
    }

    protected function qrcodePreview()
    {
        return <<<JS
$('.preview-qrcode').on('click', function () {
    var src = $(this).attr('src')
    var sample_num = $(this).data('sample-num')
    var sample_name = $(this).data('sample-name')
    layer.open({
      type: 1,
      title: sample_name + '['+ sample_num +']',
      closeBtn: 1,
      shade: 0.6,
      area: ['350px', '300px'],
      shadeClose: false,
      content: '<div style="width:100%; line-height: 245px; text-align: center;"><img src="'+src+'" /></div>'
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
        return Form::make(Sample::with(['devices', 'projects']), function (Form $form) {
            $id = $form->getKey();
            $form->display('id');
            $form->text('sample_num')->required(true)
                ->creationRules(["unique:samples"], ['unique' => '该样本号已存在'])
                ->updateRules(["unique:samples,sample_num,$id"], ['unique' => '该样本号已存在']);
            $form->text('sample_name')->required(true);
            $form->multipleSelect('projects', '试验项目')
                ->options(Project::all()->pluck('name', 'id'))
                ->customFormat(function ($v) {
                    if (!$v) return [];
                    // 这一步非常重要，需要把数据库中查出来的二维数组转化成一维数组
                    return array_column($v, 'id');
                })
                ->required(true);
            $form->multipleSelect('devices', '设备仪器')
                ->options(Device::all()->pluck('name', 'id'))
                ->customFormat(function ($v) {
                    if (!$v) return [];
                    // 这一步非常重要，需要把数据库中查出来的二维数组转化成一维数组
                    return array_column($v, 'id');
                })
                ->required(true);
            $form->text('unit_name')->required(true);
            $form->text('site_name')->required(true);
            $form->datetime('receive_at')->required(true);
            $form->text('weather')->required(true);

            $form->hidden('qrcode');

            $form->display('created_at');
            $form->display('updated_at');
        })->saving(function (Form $form) {
            if ($form->sample_num && $form->model()->get('sample_num') != $form->sample_num) {
                $qrcode = "/uploads/qrcodes/{$form->input('sample_num')}.png";
                QrCode\Facade::format('png')
                    ->size(200)
                    ->margin(1)
                    ->color(13, 126, 127)
                    ->eye('circle')
                    ->generate($form->input('sample_num'), public_path() . $qrcode);
                $form->qrcode = $qrcode;
            }

            if ($form->isCreating()) {
                $qrcode = "/uploads/qrcodes/{$form->input('sample_num')}.png";
                QrCode\Facade::format('png')
                    ->size(200)
                    ->margin(1)
                    ->color(13, 126, 127)
                    ->eye('circle')
                    ->generate($form->input('sample_num'), public_path() . $qrcode);
                $form->qrcode = $qrcode;
            }
        });
    }
}
