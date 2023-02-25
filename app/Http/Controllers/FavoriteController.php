<?php

namespace App\Http\Controllers;

use App\Models\FavoriteMovie;
use App\Models\FavoriteShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function movies(){
        $favorites = FavoriteMovie::where('user_id',Auth::id())->get();

        $resp = [];
        $resp['status'] = 200;
        $resp['success'] = true;
        $resp['results'] = $favorites;

        return response()->json($resp,200);
    }

    public function shows(){
        $favorites = FavoriteShow::where('user_id',Auth::id())->get();

        $resp = [];
        $resp['status'] = 200;
        $resp['success'] = true;
        $resp['results'] = $favorites;

        return response()->json($resp,200);
    }

    public function destroyMovie($id){
        $favorite = FavoriteMovie::findOrFail($id);

        if($favorite->delete()){
            $resp = [];
            $resp['status'] = 200;
            $resp['success'] = true;
            $resp['message'] = "the movie has been deleted from your favorite's";
        }
        else {
            $resp = [];
            $resp['status'] = 500;
            $resp['success'] = false;
            $resp['message'] = "An error has occured, please try later";
        }
    }
    
    public function destroyShow($id){
        $favorite = FavoriteShow::findOrFail($id);

        if($favorite->delete()){
            $resp = [];
            $resp['status'] = 200;
            $resp['success'] = true;
            $resp['message'] = "the movie has been deleted from your favorite's";
        }
        else {
            $resp = [];
            $resp['status'] = 500;
            $resp['success'] = false;
            $resp['message'] = "An error has occured, please try later";
        }
    }
}
