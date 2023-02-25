<?php

namespace App\Http\Controllers;

use App\Models\FavoriteShow;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ShowController extends Controller
{
    protected $api_key;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->api_key = env('TMDB_API_KEY');
    }

    public function index(Request $request){

        if($request->get('page')) $page = $request->get('page');
        else $page = 1;

        $client = new Client();

        $req = $client->request('get','https://api.themoviedb.org/3/tv/popular',[
            'query'=>['api_key'=>$this->api_key,'page'=>$page]
        ]);
        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $movies = $data->results;
                $total_pages = $data->total_pages;
                $total_results = $data->total_results;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = collect($movies)->take(10);
                $resp['page'] = $page;
                $resp['total_pages'] = $total_pages;
                $resp['total_results'] = $total_results;
            }
        }
        else{
            $data = json_decode($req->getBody());
            $resp = [];
            $resp['status'] = $req->getStatusCode();
            $resp['success'] = false;
            $resp['message'] = $data->errors[0];
        }

        return response()->json($resp,$resp['status']);

    }

    public function topRate(Request $request){

        // if($request->get('page')) $page = $request->get('page');
        // else $page = 1;

        $client = new Client();

        $req = $client->request('get','https://api.themoviedb.org/3/movie/top_rated',[
            'query'=>['api_key'=>$this->api_key]
        ]);
        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $shows = $data->results;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = collect($shows)->take(5);

            }
        }
        else{
            $data = json_decode($req->getBody());
            $resp = [];
            $resp['status'] = $req->getStatusCode();
            $resp['success'] = false;
            $resp['message'] = $data->errors[0];
        }

        return response()->json($resp, $resp['status']);

    }

    public function detail($id){

        $client = new Client();

        $req = $client->request('get',"https://api.themoviedb.org/3/tv/{$id}",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        $trl = $client->request('get',"https://api.themoviedb.org/3/tv/{$id}/videos",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        if($req->getStatusCode() == 200 && $trl->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $videos = json_decode($trl->getBody());
                $show = $data;
                $url="https://youtu.be/".collect($videos->results)->where('type','Trailer')->first()->key;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = $show;
                $resp['url_trailer'] = $url;
            }
        }
        else{
            $data = json_decode($req->getBody());
            $resp = [];
            $resp['status'] = $req->getStatusCode();
            $resp['success'] = false;
            $resp['message'] = $data->errors[0];
        }

        return response()->json($resp);

    }

    public function trailer($id){

        $client = new Client();

        $req = $client->request('get',"https://api.themoviedb.org/3/tv/{$id}/videos",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $videos = collect($data->results);
                $url="https://youtu.be/".$videos->reverse()->first()->key;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['url'] = $url;
            }
        }
        else{
            $data = json_decode($req->getBody());
            $resp = [];
            $resp['status'] = $req->getStatusCode();
            $resp['success'] = false;
            $resp['message'] = $data->errors[0];
        }

        return response()->json($resp);

    }

    public function addToFav($id){
        $client = new Client();

        $req = $client->request('get',"https://api.themoviedb.org/3/tv/{$id}",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $show = $data;

                $fav = FavoriteShow::create([
                    'show_id'=>$id,
                    'show_name'=>$show->name,
                    'user_id'=>Auth::id(),
                ]);
            }
        }

        if($fav) return response()->json(['status'=>200,'message'=>'Added to my favorite list successfly']);
        else return response()->json(['error'=>true,'message'=>'An error has occured, please try later'],500);
    }

    public function search(Request $request){

        if($request->get('page')) $page = $request->get('page');
        else $page = 1;

        if($request->get('language')) $language = $request->get('language');
        else $language = 'en';

        if($request->get('region')) $region = $request->get('region');
        else $region = "US";

        if(!$request->get('query')) return response()->json(['error'=>true,'message'=>"The query field can't be empty"],500);

        $client = new Client();

        $req = $client->request('get',"https://api.themoviedb.org/3/search/tv",[
                    'query'=>[
                        'api_key'=>$this->api_key,
                        'language'=>$language,
                        'region'=>$region,
                        'page'=>$page,
                        'query'=>$request->query
                    ]
                ]);

        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $shows = $data->results;
                $total_pages = $data->total_pages;
                $total_results = $data->total_results;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = collect($shows);
                $resp['page'] = $page;
                $resp['total_pages'] = $total_pages;
                $resp['total_results'] = $total_results;

            }
        }
        else{
            $data = json_decode($req->getBody());
            $resp = [];
            $resp['status'] = $req->getStatusCode();
            $resp['success'] = false;
            $resp['message'] = $data->errors[0];
        }

        return response()->json($resp,$resp['status']);
    }
}
