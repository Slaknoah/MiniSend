<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_POSTED  = 'posted';
    public const STATUS_SENT    = 'sent';
    public const STATUS_FAILED  = 'failed';

    protected $table = 'emails';

    protected $fillable = ['status'];

    protected $casts = [
        'receivers'     => 'array',
        'cc'            => 'array',
        'bcc'           => 'array',
        'reply_to'      => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function added_by()
    {
        return $this->belongsTo('App\Models\User', 'id', 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany('App\Models\Recipient', 'emails_recipients', 'email_id', 'recipient_id');
    }
}
