<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6',
            'confirm_password'  => 'required|same:password'
        ]);

        if ( !$validator->fails() ) {
            $user = User::create([
                'name'  => $request->get('name'),
                'email'  => $request->get('email'),
                'password'  => bcrypt( $request->get('password') ),
            ]);

//            $user->sendApiEmailVerificationNotification();

            return response()->json('', 204);
        }

        return response()->json([
            'success'   => false,
            'errors'    => $validator->errors()
        ], 422);
    }

    public function login(Request $request)
    {
        $authentication = new Authentication( $request->all() );
        return $authentication->authenticateRequest();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json('', 204);
    }
}
