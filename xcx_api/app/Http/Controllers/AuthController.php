<?php

namespace App\Http\Controllers;

use App\Models\WxUser;
use App\WXBizDataCrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    protected $openid;
    protected $sessionKey;

    public function __construct(Request $request)
    {
        $sessionId = $request->json('sessionId');
        Log::info(URL::current() . ',sessionId:' . $sessionId);
        $user = WxUser::where(['session_id' => $sessionId])->first();
        if (!$user) {
            $response = [
                'errcode' => 400,
                'msg' => '用户未登陆'
            ];
            echo json_encode($response);exit;
        }
        $this->openid = $user->openid;
        $this->sessionKey = $user->session_key;
    }

    /**
     * 解密数据
     * @param $encryptedData
     * @param $iv
     * @return Array
     */
    public function aesdecode($encryptedData, $iv)
    {
        $appid = env('YRJJ_APPID');
        $pc = new WXBizDataCrypt($appid, $this->sessionKey);
        $data = $pc->decryptData($encryptedData, $iv);
        return $data;
    }
}
