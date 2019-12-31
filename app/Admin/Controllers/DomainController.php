<?php

namespace App\Admin\Controllers;

use App\Models\Domain;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DomainController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '域名管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Domain);

        $grid->column('id', __('Id'));
        $grid->column('url', '域名');
        $grid->column('created_at', '创建时间');
        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->text('url', '域名');
        });

        $grid->disableActions();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Domain::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('url', __('Url'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Domain);

        $form->text('url', '域名');

        return $form;
    }
}
