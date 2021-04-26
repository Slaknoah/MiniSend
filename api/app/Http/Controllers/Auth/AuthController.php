<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Gravatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('logout');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6',
            'confirm_password'  => 'required|same:password'
        ]);

        $gravatar = new Gravatar( $request->get('email') );

        User::create([
            'name'  => $request->get('name'),
            'email'  => $request->get('email'),
            'password'  => bcrypt( $request->get('password') ),
            'avatar'    => $gravatar->get()
        ]);

        return response()->json('', 204);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if ( Auth::attempt([
            'email'     => $request->get('email'),
            'password'  => $request->get('password')
        ])) {
            $gravatar = new Gravatar( Auth::user()->email );
            $gravatar->update( Auth::user() );

            return response()->json('', 204);
        }

        return response()->json([
            'error' => 'invalid_credentials'
        ], 401);
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        return response()->json('', 204);
    }
}
