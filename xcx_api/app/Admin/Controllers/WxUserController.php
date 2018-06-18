<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\AddPower;
use App\Models\PowerRecord;
use App\Models\WxUser;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class WxUserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('用户管理');
            $content->description('用户列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('用户管理');
            $content->description('编辑');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('用户管理');
            $content->description('添加');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(WxUser::class, function (Grid $grid) {

//            $grid->id('ID')->sortable();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
//                dd($actions->row);
                $actions->append(new AddPower($actions->getKey()));
            });
            $grid->filter(function($filter){

                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                // 在这里添加字段过滤器
                $filter->like('nick', '昵称');

            });
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->openid('openid');
            $grid->nick('昵称');
            $grid->avatar('头像')->image();
            $grid->gender('性别')->display(function ($gender) {
                switch ($gender) {
                    case '1':
                        return '男';
                    case '2':
                        return '女';
                    default:
                        return '未知';
                }
            });
            $grid->column('元气值')->display(function () {
                $powers = PowerRecord::where(['openid' => $this->openid])->sum('num');
                return $powers;
            });
            $grid->created_at('加入时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(WxUser::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
