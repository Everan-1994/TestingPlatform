<?php

namespace App\Admin\Controllers;

use App\Models\Device;
use App\Models\Project;
use App\Models\Sample;
use SimpleSoftwareIO\QrCode;
use Dcat\Admin\Actions\Action;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class SampleController extends AdminController
{
    /**
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(Sample::with(['device', 'projects']), function (Grid $grid) {
            $grid->id->sortable();
            $grid->sample_num;
            $grid->sample_name;
            $grid->projects()->pluck('name')->label();
            $grid->column('device.name');
            $grid->unit_name;
            $grid->site_name;
            $grid->receive_at;
            $grid->weather;
            $grid->created_at->sortable();

            $grid->column('qrcode', '二维码')->display(function () {
                return env('APP_URL') . $this->qrcode;
            })->image('', 100, 100);

            $grid->actions(function ($actions) {
                $actions->disableView();
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->like('sample_num')->width(2);
                $filter->like('sample_name')->width(2);

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
        return Form::make(Sample::with(['device', 'projects']), function (Form $form) {
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
            $form->select('device_id', '设备仪器')->options(Device::all()->pluck('name', 'id'))->required(true);
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
