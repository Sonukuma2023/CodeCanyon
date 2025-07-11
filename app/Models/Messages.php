<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Messages extends Model
{
	use HasFactory;
	
    protected $table = "messages";
	
	protected $fillable = [
		'sender_id',
		'receiver_id',
		'message',	
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
