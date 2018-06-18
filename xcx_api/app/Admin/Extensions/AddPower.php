<?php
/**
 * Created by PhpStorm.
 * User: hirsi
 * Email: whuanxu@163.com
 * Github: https://github.com/Ninee
 * Date: 2018/6/17
 * Time: 下午7:25
 */

namespace App\Admin\Extensions;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\BatchAction;

class AddPower extends BatchAction
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function script()
    {
        $url = admin_url('add_powers');
        return <<<SCRIPT

$('.grid-add-power').on('click', function () {
    // Your code.
    console.log($(this).data('id'));
    var id = $(this).data('id');
    swal({
      title: "增加元气值", 
      text: "输入要增加的元气值数量：",
      type: "input", 
      showCancelButton: true, 
      closeOnConfirm: false, 
      confirmButtonText: "确定", 
      cancelButtonText: "取消",
      animation: "slide-from-top", 
      inputPlaceholder: "元气值数量" 
    },
    function(inputValue){
        var reg = /^\\d+$/;
        if(inputValue <= 0 || !reg.test(inputValue)) {
            swal('输入错误', '只能输入大于0的数字', 'error');
            return;
        }
        $.ajax({
            method: 'post',
            url: '{$url}',
            data: {
                user_id: id,
                value: inputValue,
                _token:'{$this->getToken()}'
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn grid-add-power' data-id='{$this->id}'><i class='fa fa-plus'></i>增加元气值</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}