<?php


namespace App\Services;


use Illuminate\Database\Eloquent\Model;

class Gravatar
{
    private $email;
    private $size;

    public function __construct( $email, $size = 100 )
    {
        $this->email = $email;
        $this->size = $size;
    }

    public function update( Model $model, $fieldName = 'avatar' )
    {
        $model->update([
            $fieldName => $this->get()
        ]);
    }

    public function get()
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $this->email ) ) );
        $url .= '?s=' . $this->size .'&r=g';

        return $url;
    }
}
