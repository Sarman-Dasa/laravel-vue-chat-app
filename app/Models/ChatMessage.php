<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory,SoftDeletes;


    public $fillable = ['sender_id','receiver_id','message','is_edited','send_at', 'is_sent'];


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
}
