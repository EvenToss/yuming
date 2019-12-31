<?php

namespace App\Admin\Controllers;

use App\Models\History;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class HistoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '域名历史';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new History);

        $grid->column('id', __('Id'));
        $grid->column('domain.url', '域名');
        $grid->column('time', '日期')->sortable();
        $grid->column('pc_weight', 'PC权重')->sortable();
        $grid->column('m_weight', '移动权重')->sortable();
        $grid->column('pc_vocabulary', 'PC词量')->sortable();
        $grid->column('m_vocabulary', '移动词量')->sortable();
        $grid->column('created_at', '创建时间');
        $grid->model()->orderBy('pc_vocabulary', 'desc');
        $grid->disableCreateButton();
        $grid->disableActions();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('domain_id', '域名id');
            $filter->like('domain.url', '域名');
            $filter->between('time', '历史时间')->date();

        });


        return $grid;
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
        $show = new Show(History::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('time', __('Time'));
        $show->field('pc_weight', __('Pc weight'));
        $show->field('m_weight', __('M weight'));
        $show->field('pc_vocabulary', __('Pc vocabulary'));
        $show->field('m_vocabulary', __('M vocabulary'));
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
        $form = new Form(new History);

        $form->datetime('time', __('Time'))->default(date('Y-m-d H:i:s'));
        $form->number('pc_weight', __('Pc weight'));
        $form->number('m_weight', __('M weight'));
        $form->number('pc_vocabulary', __('Pc vocabulary'));
        $form->number('m_vocabulary', __('M vocabulary'));
        $form->number('domain_id', __('Domain id'));

        return $form;
    }
}
