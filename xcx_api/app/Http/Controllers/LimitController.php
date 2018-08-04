<?php

namespace App\Http\Controllers;

use App\Models\AdBanner;
use App\Models\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LimitController extends AuthController
{
    public function getLimit(Request $request)
    {
        $openid = $this->openid;
        $limit = Limit::where(['openid' => $openid])->first();
        if ($limit) {
            return response()->json(['limitData' => json_decode($limit->limit_data)]);
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

    public function getAd()
    {
        $ad = AdBanner::where(['appid' => env('LIMIT_APPID')])->first();
        if ($ad) {
            return response()->json(['image_url' => Storage::disk(config('admin.upload.disk'))->url($ad->image_url)]);
        } else {
            return response()->json(null);
        }
    }
}
