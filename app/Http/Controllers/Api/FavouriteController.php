<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use Auth;
use Validator;
class FavouriteController extends Controller
{
    public function add_fav_bar(request $request)
    {
        $rules['restaurantId'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);   
        $user = Auth::user();
        $model = new Favourite();
        $request->userId = $user->userId;
       
        $response=$model->addFavoutireBar($request);
        return response()->json(['status' =>true , 'message' =>'success'], 200);
    }
}
