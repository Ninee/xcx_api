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
