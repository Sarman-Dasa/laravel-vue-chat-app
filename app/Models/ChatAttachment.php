<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAttachment extends Model
{
    use HasFactory;

    public $fillable = ['message_id','file_path','file_name','is_audio_file'];
    protected $appends = ['timeAgo'];

    protected $casts = [
        'is_audio_file' => 'boolean',
    ];

    public function gettimeAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
