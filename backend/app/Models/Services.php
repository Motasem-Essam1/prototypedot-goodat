<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{

    use HasFactory, SoftDeletes;
    protected $fillable = [
      'user_id',
      'category_id',
      'parent_category_id',
      'service_name',
      'service_slug',
      'service_description',
      'starting_price',
      'ending_price',
      'location_lng',
      'location_lat',
      'is_active'
    ];

    protected $appends = [
        'is_like',
        'likes_count'
    ];

    /**
     * The images that belong to the Unit.
     */
    public function getIsLikeAttribute() {
        if (Auth()->check()) {
            return Favorite::where('user_id', Auth()->user()->id)->where('favorite_id', $this->id)->where('item_type', 'service')->count() > 0 ? 1 : 0;
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
        $count = Favorite::where('favorite_id', $this->id)->where('item_type', 'service')->count();
        return $this->custom_number_format($count);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Images::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'category_id');
    }

    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->with('user');
    }

    public function CustomerReview()
    {
        return $this -> belongsTo(CustomerReview::class, 'id','review_id')->where('review_type', 'service_average');
    }


    protected static function boot() {

        parent::boot();

        static::deleting(function($service) {
            $service->images->each(function ($image) {
                $image->delete();
            });
            $service->reviews->each(function ($review) {
                $review->delete();
            });
        });
    }

}
