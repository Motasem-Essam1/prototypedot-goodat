<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable =[
        'category_id',
        'sub_category_name',
        'sub_category_slug',
        'sub_category_image',
        'is_active'
    ];


    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Services::class);
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function UserSubCategories()
    {
        return $this->hasMany(UserSubCategories::class, 'user_category_id', 'id');
    }

}
