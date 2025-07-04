<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends Model
{
    use HasFactory;
	
	protected $table = "notifications";
	
	protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'url',
        'sent_at',
        'read_at',
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
