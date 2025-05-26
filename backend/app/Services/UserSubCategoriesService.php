<?php

namespace App\Services;

use App\Models\Category;
use App\Models\UserSubCategories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class UserSubCategoriesService
{

    public function __construct()
    {

    }

    /**
     * get Categories
     *
     * @return string
     */
    public function getUserSubCategoriesText(int $id): string
    {
        $user_sub_categories = UserSubCategories::with('SubCategory')->where('user_id', $id)->get()->toArray();
        return implode(", ",  array_map(function ($item) {
                return $item['sub_category']['sub_category_name'];
            }, $user_sub_categories)
        );
    }

}
