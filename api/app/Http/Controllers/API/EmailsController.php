<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmailRequest;
use App\Http\Resources\EmailResource;
use App\Services\Email\CreateEmail;
use App\Services\Email\LoadEmails;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('index', 'store');
    }

    public function index( Request $request )
    {
        $loadEmails = new LoadEmails( $request->all() );
        $emails = $loadEmails->load();

        return ( EmailResource::collection($emails) )->response();
    }

    public function store( CreateEmailRequest $request )
    {
        $createEmail = new CreateEmail( $request->all() );
        $email       = $createEmail->save();

        return ( new EmailResource( $email ) )->response();
    }
}
