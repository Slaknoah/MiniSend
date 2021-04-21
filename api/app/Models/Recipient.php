<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['email', 'name'];

    public function emails()
    {
        return $this->belongsToMany('App\Models\Email', 'emails_recipients', 'recipient_id', 'email_id');
    }

}
