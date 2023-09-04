<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionRevenueController extends Controller
{
    //
    public function GetSubscriptionRevenue(Request $request){
        $currDt = Carbon::now();
        $thirtyDaysBef = $currDt->subDays(30);

        // format the date
        $startDt = $thirtyDaysBef->format('Y-m-d');
        $endDt = $currDt->format('Y-m-d');

        if(Auth::check()){
            $memberId = Auth::user()->id;
            if (Gate::allows('view-stored-procedure')) {
                $results = DB::select('CALL GetSubscriptionRevenue('.$memberId.',\''.$startDt.'\',\''.$endDt.'\')');
                return response()->json($results);
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }else{
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
    }
}
