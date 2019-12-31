<?php

namespace App\Admin\Controllers;

use App\Models\Juzi;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class JuziController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '橘子历史';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Juzi);

        $grid->column('id', 'id');
        $grid->column('domain.url', '域名');
        $grid->column('year', '历史年份');
        $grid->column('title', '历史标题');
        $grid->column('created_at', '创建时间');
        $grid->model()->orderBy('created_at', 'desc');
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
        $show = new Show(Juzi::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('year', __('Year'));
        $show->field('title', __('Title'));
        $show->field('domain_id', __('Domain id'));
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
        $form = new Form(new Juzi);

        $form->date('year', __('Year'))->default(date('Y-m-d'));
        $form->text('title', __('Title'));
        $form->number('domain_id', __('Domain id'));

        return $form;
    }
}
