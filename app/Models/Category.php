<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_name',
        'category_slug',
        'category_image',
        'is_active'
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    public function subCategoriesActive(): HasMany
    {
        return $this->hasMany(SubCategory::class)->where('is_active', 1);
    }
}
