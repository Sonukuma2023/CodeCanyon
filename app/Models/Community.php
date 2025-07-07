<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Community extends Model
{
    use HasFactory;

    protected $table = "community";

	protected $fillable = [
		'user_id',
		'complaint',
		'comment',
		'admin_reply',
		'developer_reply',
	];

	public function user()
    {
        return $this->belongsTo(User::class);
    }
}
