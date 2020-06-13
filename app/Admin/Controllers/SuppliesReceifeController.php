<?php

namespace App\Admin\Controllers;

use App\Models\SuppliesReceife;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class SuppliesReceifeController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new SuppliesReceife(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->supplies_id;
            $grid->stock;
            $grid->add_stock;
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
        return Show::make($id, new SuppliesReceife(), function (Show $show) {
            $show->id;
            $show->supplies_id;
            $show->stock;
            $show->add_stock;
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
        return Form::make(new SuppliesReceife(), function (Form $form) {
            $form->display('id');
            $form->text('supplies_id');
            $form->text('stock');
            $form->text('add_stock');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
