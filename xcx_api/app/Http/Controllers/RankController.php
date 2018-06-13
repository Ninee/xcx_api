<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankController extends Controller
{

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
