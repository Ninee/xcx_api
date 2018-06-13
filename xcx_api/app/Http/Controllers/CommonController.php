<?php

namespace App\Http\Controllers;

use App\WXBizDataCrypt;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function aesdecode(Request $request)
    {
        $appid = env('YRJJ_APPID');
        $appid = 'wx4f4bc4dec97d474b';
        $sessionKey = $request->json('sessionKey');
        $encryptedData = $request->json('encryptedData');
        $iv = $request->json('vi');
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $data = $pc->decryptData($encryptedData, $iv);

        return response()->json($data);
    }

}
