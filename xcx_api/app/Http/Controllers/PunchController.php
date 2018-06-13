<?php

namespace App\Http\Controllers;

use App\Models\PowerRecord;
use App\Models\Punch;
use App\Models\Quote;
use App\Models\WxUser;
use Illuminate\Http\Request;

class PunchController extends AuthController
{
    public function wxSteps(Request $request)
    {
        $encryptedData = $request->json('encryptedData');
        $iv = $request->json('iv');
        $data = $this->aesdecode($encryptedData, $iv);
        $response = $data;
        return response()->json($response);
    }

    public function punch(Request $request)
    {
        $today = date('Y-m-d', time());
        $punch = Punch::where(['openid' => $this->openid, 'punched_at' => $today])->first();
        if ($punch) {
            return response()->json([
                'errcode' => '201',
                'msg' => '今天已经打卡'
            ]);
        }
        $result = Punch::create(['openid' => $this->openid, 'steps' => $request->json('steps'), 'punched_at' => $today]);
        $power = PowerRecord::create(['openid' => $this->openid, 'num' => 8]);
        return response()->json($result->toArray());
    }

    public function historySteps(Request $request)
    {
        $steps = Punch::where(['openid' => $this->openid])->sum('steps');
        return response($steps);
    }

    public function historyPunch(Request $request)
    {
        $puch = Punch::where(['openid' => $this->openid])->count();
        return response($puch);
    }

    public function quote(Request $request)
    {
        $sum = Quote::all()->count();
        $rand = rand(1,$sum);
        $quote = Quote::offset($rand-1)->limit(1)->pluck('contents')->first();
        return response()->json(['quote' => $quote]);
    }

    public function powers(Request $request)
    {
        $powers = PowerRecord::where(['openid' => $this->openid])->sum('num');
        return response($powers);
    }

    public function powerRecord(Request $request)
    {
        $power_record = PowerRecord::orderBy('id', 'desc')->paginate(15);
        return response($power_record->toJson());
    }
}
