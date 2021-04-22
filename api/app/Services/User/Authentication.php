<?php


namespace App\Services\User;


use App\Services\Gravatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Authentication
{
    private $data;

    public function __construct( $data )
    {
        $this->data = $data;
    }

    public function authenticateRequest()
    {
        $validator = Validator::make( $this->data, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if ( !$validator->fails() ) {
            // Attempt to log in user and if successful send a 204 response
            if ( Auth::attempt([
                'email'     => $this->data['email'],
                'password'  => $this->data['password']
            ])) {
                $gravatar = new Gravatar( Auth::user()->email );
                $gravatar->update( Auth::user() );

                return response()->json('', 204);
            } else {
                return response()->json([
                    'error' => 'invalid_credentials'
                ], 403);
            }
        }

        return response()->json([
            'error' => 'invalid_credentials'
        ], 403);
    }
}
