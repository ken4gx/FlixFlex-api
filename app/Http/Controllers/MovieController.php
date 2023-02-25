<?php

namespace App\Http\Controllers;

use App\Models\FavoriteMovie;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
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

        $req = $client->request('get','https://api.themoviedb.org/3/movie/popular',[
            'query'=>['api_key'=>$this->api_key,'page'=>$page]
        ]);

        $top = $client->request('get','https://api.themoviedb.org/3/movie/top_rated',[
            'query'=>['api_key'=>$this->api_key]
        ]);


        if($req->getStatusCode() == 200  && $top->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $movies = $data->results;
                $total_pages = $data->total_pages;
                $total_results = $data->total_results;

                $top_movies = json_decode($top->getBody());

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = collect($movies)->take(10);
                $resp['top_movies'] = collect($top_movies->results)->take(5);
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

        return response()->json($resp);

    }

    public function topRate(Request $request){

        $client = new Client();

        $req = $client->request('get','https://api.themoviedb.org/3/movie/top_rated',[
            'query'=>['api_key'=>$this->api_key]
        ]);
        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $movies = $data->results;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = collect($movies)->take(5);
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

    public function detail($id){

        $client = new Client();

        $req = $client->request('get',"https://api.themoviedb.org/3/movie/{$id}",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        $trl = $client->request('get',"https://api.themoviedb.org/3/movie/{$id}/videos",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        if($req->getStatusCode() == 200 && $trl->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $videos = json_decode($trl->getBody());
                $movie = $data;
                $url="https://youtu.be/".collect($videos->results)->where('type','Trailer')->first()->key;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = $movie;
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

        $req = $client->request('get',"https://api.themoviedb.org/3/movie/{$id}/videos",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $videos = collect($data->results);
                $url="https://youtu.be/".$videos->where('type','Trailer')->first()->key;

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

        $req = $client->request('get',"https://api.themoviedb.org/3/movies/{$id}",[
            'query'=>['api_key'=>$this->api_key]
        ]);

        if($req->getStatusCode() == 200){
            if($req->getBody()){
                $data = json_decode($req->getBody());
                $show = $data;

                $fav = FavoriteMovie::create([
                    'movie_id'=>$id,
                    'movie_name'=>$show->name,
                    'user_id'=>Auth::id(),
                ]);
            }
        }

        $fav = FavoriteMovie::create([
            'movie_id'=>$id,
            'user_id'=>Auth::id(),
        ]);

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

        $req = $client->request('get',"https://api.themoviedb.org/3/search/movies",[
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
                $movies = $data->results;
                $total_pages = $data->total_pages;
                $total_results = $data->total_results;

                $resp = [];
                $resp['status'] = 200;
                $resp['success'] = true;
                $resp['results'] = collect($movies);
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
