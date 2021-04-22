<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('create', 'index');
    }

    public function create(Request $request)
    {
        $request->validate( [ 'token_name' => 'required|string'] );

        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken];
    }

    public function index(Request $request)
    {
        return ['tokens' => $request->user()->tokens];
    }
}
