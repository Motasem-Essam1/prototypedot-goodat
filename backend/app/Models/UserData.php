<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserData extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "user_data";
    protected $fillable = [
        'user_id',
        'package_id',
        'phone_number',
        'verify_code',
        'phone_verify_at',
        'user_type',
        'provider_id',
        'provider_type',
        'country_code',
        'avatar',
        'generated_Code',
        'number_of_invites',
        'nominated_by'
    ];

    protected $appends = [
        'is_like',
        'likes_count',
        'phone_status'
    ];

    public function getIsLikeAttribute() {
        if (Auth()->check()) {
            return Favorite::where('user_id', Auth()->user()->id)->where('favorite_id', $this->user_id)->where('item_type', 'provider')->count() > 0 ? 1 : 0;
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

    public function getPhoneStatusAttribute() {
        if (Auth()->check()) {
            if ($this->package_id != null) {
                $result = Package::where('id', $this->package_id)->first();
                return isset($result->phone_status) ? $result->phone_status : '';
            }
        }
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function UserSubCategories()
    {
        return $this->hasMany(UserSubCategories::class, 'user_id', 'user_id');
    }

}
