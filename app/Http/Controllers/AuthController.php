<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','subscribe']]);
    }

    /**
     * Create a new user instance.
     *
     * @return response
     */
    public function subscribe(Request $request){
        // validate data from request
        $rules= [
            'username'=>'required|string',
            'email'=>'required|email',
            'password'=>'required|confirmed|min:5'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            $resp=[];
            $resp['status'] = 500;
            $resp['error'] = true;
            $resp['message'] = $validator->errors()->first();

            return response()->json($resp,500);
        }

        // create the user

        $user = User::create([
            'name'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        // construct the response

        $resp = [];
        if($user) {
            $resp['status'] = 200;
            $resp['success'] = true;
            $resp['message'] = "The user was created successfly";
        }
        else{
            $resp['status'] = 500;
            $resp['error'] = true;
            $resp['message'] = "An error has occured, please try later";
        }

        return response()->json($resp);

    }

    public function username()
    {
        return 'username';
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['username', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
