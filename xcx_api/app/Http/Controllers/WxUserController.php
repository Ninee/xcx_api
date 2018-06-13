<?php

namespace App\Http\Controllers;

use App\Models\WxUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WxUserController extends AuthController
{
    public function index()
    {
       return WxUser::all();
    }

    public function show($id)
    {
        return WxUser::find($id);
    }

    public function store(Request $request)
    {
        $wx_user = WxUser::create($request->all());
        return response()->json($wx_user, 201);
    }

    public function update(Request $request, $id)
    {
        $wx_user = WxUser::findOrFail($id);
        $wx_user->update($request->all());
        return $wx_user;
    }

    public function delete(Request $request, $id)
    {
        $wx_user = WxUser::findOrFail($id);
        $wx_user->delete();
        return response()->json(null, 204);
    }

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

    /**
     * 更新用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserInfo(Request $request)
    {
        $userinfo = $request->json('userInfo');
        $user = WxUser::where(['openid' => $this->openid])->first();
        $user->nick = $userinfo['nickName'];
        $user->avatar = $userinfo['avatarUrl'];
        $user->gender = $userinfo['gender'];
        $user->city = $userinfo['city'];
        $user->province = $userinfo['province'];
        $user->country = $userinfo['country'];
        $user->language = $userinfo['language'];
        $result = $user->save();
        if ($result) {
            return response()->json([
                'errcode' => 0,
                'msg' => 'success',
            ]);
        }
        return response()->json([
            'errcode' => 3001,
            'msg' => 'fail',
        ]);
    }
}
