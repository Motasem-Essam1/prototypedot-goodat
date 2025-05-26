<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'country_code',
        'password',
        'status',
        'phoned_Signed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'is_like',
        'likes_count'
    ];

    /**
     * @return HasOne
     */

    public function getIsLikeAttribute() {
        if (Auth()->check()) {
            return Favorite::where('user_id', Auth()->user()->id)->where('favorite_id', $this->id)->where('item_type', 'provider')->count() > 0 ? 1 : 0;
        }
    }

    function custom_number_format($n, $precision = 1) {
        if ($n < 1000) {
            $n_format = number_format($n);
        } else if ($n < 1000000) {
            $n_format = number_format($n / 1000, $precision) . 'K';
        }  else if ($n < 1000000000) {
            $n_format = number_format($n / 1000000, $precision) . 'M';
        } else {
            $n_format = number_format($n / 1000000000, $precision) . 'B';
        }

        return $n_format;
    }

    public function getLikesCountAttribute() {
        $count = Favorite::where('favorite_id', $this->id)->where('item_type', 'provider')->count();
        return $this->custom_number_format($count);
    }

    public function user_data(): HasOne
    {
        return $this->hasOne(UserData::class);
    }

    public function user_favorite(): HasOne
    {
        return $this->hasOne(Favorite::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Services::class)->with('reviews');
    }

    public function servicesActive(): HasMany
    {
        return $this->hasMany(Services::class)->with('reviews')->where('is_active', 1);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function UserSubCategories()
    {
        return $this->hasMany(UserSubCategories::class, 'user_id', 'id');
    }

    public function notify()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    public function device_tokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    protected static function boot() {

        parent::boot();

        static::deleting(function($user) {
            $user->services->each(function ($service) {
                $service->images->each(function ($image) {
                    $image->delete();
                });
                $service->reviews->each(function ($review) {
                    $review->delete();
                });
            });
            $user->tasks->each(function ($task) {
                $task->images->each(function ($image) {
                    $image->delete();
                });
                $task->reviews->each(function ($review) {
                    $review->delete();
                });
            });
            $user->services()->delete();
            $user->tasks()->delete();
            $user->user_data()->delete();
        });
    }

    public function CustomerReview()
    {
        return $this -> belongsTo(CustomerReview::class, 'id','review_id');
    }
}
