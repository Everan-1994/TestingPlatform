<?php

use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Show;

Grid::resolving(function (Grid $grid) {
    $grid->disableRowSelector();
});

Form::resolving(function (Form $form) {
    $form->disableViewButton();
});

