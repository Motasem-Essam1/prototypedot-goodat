<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $category = Category::create([
//            'category_name' => "",
//            'category_slug'=>Str::slug('')
//        ]);

        $data = array(
            [
                'name'=> 'House Work',
                'subCategory' => array(
                    ['name' => 'cleaning'],
                    ['name' => 'painting'],
                    ['name' => 'plumbing'],
                    ['name' => 'electric'],
                    ['name' => 'tiling']
                )
            ],
            [
                'name'=> 'Caring',
                'subCategory' => array(
                    ['name' => 'Adult care'],
                    ['name' => 'Child minding'],
                    ['name' => 'Pet care']
                )
            ],
            [
                'name'=> 'Cooking/ Baking',
                'subCategory' => array(
                    ['name' => 'Sweets'],
                    ['name' => 'Baker'],
                    ['name' => 'Cakes'],
                    ['name' => 'Birthday cake'],
                    ['name' => 'Cooking']
                )
            ],
            [
                'name'=> 'Arts / Design',
                'subCategory' => array(
                    ['name' => 'Painting'],
                    ['name' => 'Photo shooting'],
                    ['name' => 'Styling'],
                    ['name' => 'Makeup']
                )
            ]
        );
        foreach ($data as $value) {
            $category = Category::create([
                'category_name' => $value['name'],
                'category_slug' => Str::of($value['name'])->slug('-')
            ]);

            foreach ($value['subCategory'] as $value) {
                SubCategory::create([
                    'sub_category_name' => $value['name'],
                    'sub_category_slug' => Str::of($value['name'])->slug('-'),
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
