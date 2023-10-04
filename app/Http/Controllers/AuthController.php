<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use  App\Models\User;
use  App\Models\Children;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh', 'logout']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */

    public function register(Request $request)
    {
        
        // Are the proper fields present?
        $this->validate($request, [
            "name" => "required|string|between:2,100", 
            "address" => "required|string|max:120", 
            "phone" => "required|string|max:18",
            "password" => "required|string|min:6",
            "role" => 'required|in:1,2,3,4'
        ]);
 
        try {
            $user = new User;
            $user->name = $request->input("name");
            $user->number = $request->input("phone");
            $user->email = $request->input("email");
            $user->address = $request->input("address");  
            $plainPassword = $request->input("password");
            $user->password = app("hash")->make($plainPassword);
            $user->role = $request->input('role');
            $user->save(); 
            return response()->json(["user" => $user, "message" => "CREATED"], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "User Registration Failed!", "error" => $e->getMessage()], 409);
        }
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
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
    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => auth()->user()->is_actived===1 ? $token : null,
            'role' => auth()->user()->role,
            'user' => auth()->user()->status===1 ? auth()->user()->name : null,
            'referral_code' => auth()->user()->referral_code,
            // 'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ]);
    }
}