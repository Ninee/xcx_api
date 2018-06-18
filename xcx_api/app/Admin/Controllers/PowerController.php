<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PowerRecord;
use App\Models\WxUser;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class PowerController extends Controller
{
    use ModelForm;

    public function add(Request $request)
    {
        $user_id = $request->input('user_id');
        $value = $request->input('value');
        $user = WxUser::find($user_id);
        $power = PowerRecord::create(['openid' => $user->openid, 'num' => $value]);
        if ($power) {
            return response()->json([
                'status' => 1,
                'message' => '成功增加元气值'
            ]);
        } else {
            return response()->json([
                'status' => -1,
                'message' => '增加元气值失败,请重试'
            ]);
        }
    }
}
