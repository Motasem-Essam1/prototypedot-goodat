<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubCategories extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_category_id'
    ];


    public function SubCategory(){
        return $this -> belongsTo(SubCategory::class,'user_category_id','id');
    }

}
