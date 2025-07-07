<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'image',
        'role',
        'status',
    ];    
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
	
	
	public function sentMessages()
	{
		return $this->hasMany(Messages::class, 'sender_id');
	}

	public function receivedMessages()
	{
		return $this->hasMany(Messages::class, 'receiver_id');
	}
	
	public function receivedNotifications()
	{
		return $this->hasMany(Notification::class, 'receiver_id');
	}

	public function sentNotifications()
	{
		return $this->hasMany(Notification::class, 'sender_id');
	}

    public function wishlist()
	{
		return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id')->withTimestamps();;
	}

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupons::class);
    }

}
