<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class CategoryService
{

    public function __construct()
    {

    }

    /**
     * get Categories
     *
     * @return Builder|Collection
     */
    public function getCategories(): Builder|Collection|null
    {
        return Category::with(['subCategories' => fn($query) => $query->where('is_active', '1')])->where('is_active', true)->get();
    }

}
