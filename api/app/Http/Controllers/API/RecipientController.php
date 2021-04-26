<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Resources\RecipientResource;
use App\Models\Recipient;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('show', 'index');
    }

    public function index(Request $request)
    {
        return ( RecipientResource::collection( Recipient::paginate(10) ) )->response();
    }

    public function show(Recipient $recipient)
    {
        return ( new RecipientResource($recipient) )->response();
    }
}
