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
        return response()->json($limit);
    }

    public function putLimit(Request $request)
    {
        $openid = $this->openid;

        $limit = Limit::where(['openid' => $openid])->first();
        if ($limit) {
            $limit->limit_data = $request->json('limitData');
            $limit->save();
        } else {
            $limit = new Limit();
            $limit->openid = $openid;
            $limit->limit_data = $request->json('limitData');
            $limit->save();
        }
        return response()->json($limit);
    }
}
