<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Resources\RecipientResource;
use App\Models\Recipient;

class RecipientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('show');
    }

    public function show(Recipient $recipient)
    {
        return ( new RecipientResource($recipient) )->response();
    }
}
