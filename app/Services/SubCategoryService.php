<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Package;
use App\Models\SubCategory;


class SubCategoryService
{

    public function __construct()
    {

    }

    /**
     * get Categories by id
     *
     * @param int $id
     * @return SubCategory
     */
    public function getSubCategoryByCategoryId(int $id)
    {
        $sub_category = SubCategory::select('id')->where('is_active', 1)->where('category_id', $id)->get();
        return $sub_category;
    }

    public function getSubCategoryByCategoryIdWithUser(int $id)
    {
        $sub_categories = SubCategory::where('is_active', 1)->with('UserSubCategories')->where('category_id', $id)->get();
        return $sub_categories;
    }

    public function checkCategoryIsActiveBySubCategoryId(int $id)
    {
        $sub_categories = SubCategory::query()->where('id', $id)->first();
        $category = Category::query()->where('id', $sub_categories['category_id'])->first();
        if($category['is_active'] == 1)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function checkSubCategoryIsActiveBySubCategoryId(int $id)
    {
        $sub_categories = SubCategory::query()->where('id', $id)->first();
        if($sub_categories['is_active'] == 1)
        {
            return true;
        }
        else{
            return false;
        }
    }
}
