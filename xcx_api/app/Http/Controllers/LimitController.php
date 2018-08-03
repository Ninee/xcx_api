<?php

namespace App\Http\Controllers;

use App\Models\Limit;
use Illuminate\Http\Request;

class LimitController extends AuthController
{
    public function getLimit(Request $request)
    {
        $openid = $this->openid;
        $limit = Limit::where(['openid' => $openid])->first();
        if ($limit) {
            return response()->json(['limitData' => $limit->limit_data]);
        } else {
            return response()->json(['limitData' => []]);
        }
    }

    public function putLimit(Request $request)
    {
        $openid = $this->openid;

        $limit = Limit::where(['openid' => $openid])->first();
        if ($limit) {
            $limit->limit_data = json_encode($request->json('limitData'));
            $limit->save();
        } else {
            $limit = new Limit();
            $limit->openid = $openid;
            $limit->limit_data = json_encode($request->json('limitData'));
            $limit->save();
        }
        return response()->json($limit);
    }
}
