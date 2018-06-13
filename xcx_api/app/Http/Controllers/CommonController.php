<?php

namespace App\Http\Controllers;

use App\Models\PerImage;
use App\Models\PowerRecord;
use App\Models\Quote;
use App\Models\WxUser;
use App\WXBizDataCrypt;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    /**
     * 获取或者更新session_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginSession(Request $request)
    {
        $code = $request->json('code');
        $client = new Client();
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . env('YRJJ_APPID') . '&secret='. env('YRJJ_SECRET') . '&js_code=' . $code .'&grant_type=authorization_code';
        $res = $client->request('GET', $url);
        $data = json_decode($res->getBody(), true);
        $response = [];
        if (key_exists('errcode', $data)) {
            $response = $data;
        } else {
            $openid = $data['openid'];
            $session_key = $data['session_key'];
            $user = WxUser::where(['openid' => $openid])->first();
//            $response['data'] = $data;
            if ($user) {
                $user->openid = $openid;
                $user->session_key = $session_key;
                $user->save();
                $response['session_id'] = $user->session_id;
            } else {
                $session_id = md5(env('YRJJ_SECRET') . $openid);
                $user = new WxUser();
                $user->appid = env('YRJJ_APPID');
                $user->openid = $openid;
                $user->session_key = $session_key;
                $user->session_id = $session_id;
                $user->save();
                $response['session_id'] = $session_id;
            }
        }
        return response()->json($response);
    }

    public function quote(Request $request)
    {
        $sum = Quote::all()->count();
        $rand = rand(1,$sum);
        $quote = Quote::offset($rand-1)->limit(1)->pluck('contents')->first();
        return response()->json(['quote' => $quote]);
    }

    public function perImage()
    {
        $sum = PerImage::all()->count();
        $rand = rand(1,$sum);
        $image_url = PerImage::offset($rand-1)->limit(1)->pluck('image_url')->first();
        return response()->json(['per_image' => Storage::disk(config('admin.upload.disk'))->url($image_url)]);
    }

    public function powerRecord(Request $request)
    {
        $sessionId = $request->input('sessionId');
        $user = WxUser::where(['session_id' => $sessionId])->first();
        if (!$user) {
            $response = [
                'errcode' => 400,
                'msg' => '用户未登陆'
            ];
            echo json_encode($response);exit;
        }
        $power_record = PowerRecord::where(['openid' => $user->openid])->orderBy('id', 'desc')->paginate(15);
        return response($power_record->toJson());
    }

}
