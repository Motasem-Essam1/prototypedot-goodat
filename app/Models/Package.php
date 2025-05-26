<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_name',
        'number_of_services',
        'number_of_images_per_service',
        'search_package_priority',
        'tasks_notification_criteria',
        'is_public',
        'per_month',
        'has_condition',
        'has_price',
        'price',
        'slug',
        'description',
        'image',
        'color',
        'phone_status'
    ];

    public function users():HasMany
    {
        return $this->hasMany(UserData::class);
    }

}
