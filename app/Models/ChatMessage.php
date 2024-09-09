<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory,SoftDeletes;


    public $fillable = ['sender_id','receiver_id','message','is_edited','send_at', 'is_sent'];

    protected $appends = ['timeAgo'];

    protected $casts = [
        'is_edited' => 'boolean',
        'send_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function gettimeAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function attachments() {
        return $this->hasMany(ChatAttachment::class,'message_id','id');
    }
}
