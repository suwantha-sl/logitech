<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class GetEvents extends Controller
{
    //
    public function RetieveMyEvents(Request $request)
    {
        $recPerPage = 100;       
        $pageNumber = $request->input('page',1);
        $stVal = ($pageNumber - 1) * $recPerPage;

        if(Auth::check()){
            $memberId = Auth::user()->id;
            if (Gate::allows('view-stored-procedure')) {
                $results = DB::select('CALL GetEventList('.$memberId.','.$stVal.',100)');
                return response()->json($results);
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }else{
            return response()->json(['message' => 'Unauthorized'], 403);
        }       
        
    }
}
