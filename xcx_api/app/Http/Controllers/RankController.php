<?php

namespace App\Http\Controllers;

use App\Models\GroupOpenid;
use App\Models\WxUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankController extends AuthController
{
    public function openGid(Request $request)
    {
        $openGid = $request->json('openGid');
        $exist = GroupOpenid::where(['openid' => $this->openid, 'opengid' => $openGid])->first();
        if (!$exist) {
            $exist = GroupOpenid::create(['openid' => $this->openid, 'opengid' => $openGid]);
            return response()->json($exist);
        } else {
            return response()->json([
                'errcode' => '200',
                'msg' => '数据已存在'
            ]);
        }
    }

    public function rank(Request $request)
    {
        $type = $request->json('type');
        $openGid = $request->json('openGid');
        $sql = <<<SQL
SELECT wx_users.openid,wx_users.avatar, wx_users.nick, punches.steps 
FROM wx_users,punches,group_openids 
WHERE punches.openid = wx_users.openid
AND punches.openid = group_openids.openid
AND group_openids.opengid = ?
AND punches.punched_at = ?
ORDER BY punches.steps DESC
SQL;
        $week_sql = <<<SQL
SELECT wx_users.openid,wx_users.avatar, wx_users.nick, sum(punches.steps) as steps
FROM wx_users,punches,group_openids 
WHERE punches.openid = wx_users.openid
AND punches.openid = group_openids.openid
AND group_openids.opengid = ?
AND punches.punched_at >= ?
GROUP BY punches.openid
ORDER BY sum(punches.steps) DESC
SQL;

        $all = GroupOpenid::where(['opengid' => $openGid])->pluck('openid')->toArray();
        $rank = null;
        $data = [];
        switch ($type)
        {
            case 'today':
                $dateRange = date('Y-m-d', time());
                $rank = DB::select($sql, [$openGid, $dateRange]);
                $data['rank'] = $rank;
                break;
            case 'yesterday':
                $dateRange =  date('Y-m-d', strtotime("-1 day"));
                $rank = DB::select($sql, [$openGid, $dateRange]);
                $diff = [];
                foreach ($rank as $key => $item) {
                    $diff[] = $item->openid;
                }

                $others = array_diff($all, $diff);
                $remain = [];
                foreach ($others as $other) {
                    $user = WxUser::where(['openid' => $other])->first(['openid', 'avatar', 'nick'])->toArray();
                    $remain[] = $user;
                }
                $data['rank'] = $rank;
                $data['others'] = $remain;
                break;
            case 'week':
                //本周一
                $dateRange = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //w为星期几的数字形式,这里0为周日
//                print_r($dateRange);exit;
                $rank = DB::select($week_sql, [$openGid, $dateRange]);
                $data['rank'] = $rank;
                break;
        }
        return response()->json($data);
    }

//    public function rank(Request $request)
//    {
//        $type = $request->json('type');
//        $dateRange = '';
//        switch ($type)
//        {
//            case 'today':
//                $dateRange = "punched_at = '" . date('Y-m-d', time());
//                break;
//            case 'yesterday':
//                $dateRange = "punched_at = '" . date('Y-m-d', strtotime("-1 day"));
//                break;
//            case 'week':
//                break;
//        }
//        $sql = <<<SQL
//SELECT
//    a.openid,
//    a.score,
//    @rownum := @rownum + 1 AS rank
//FROM
//    (
//        SELECT
//            openid,
//            sum(steps) AS score
//        FROM
//            punches
//        GROUP BY
//            openid
//        ORDER BY
//            steps
//	    DESC
//    ) AS a,
//    (SELECT @rownum := 0) r
//SQL;
////        print_r($sql);exit;
//        $result = DB::select($sql, []);
//        return response()->json($result);
//    }
}
