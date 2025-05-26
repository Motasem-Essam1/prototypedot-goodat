<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images_task extends Model
{
    protected $table = "images_task";
    use HasFactory;
    protected $fillable = [
        'images_id',
        'task_id'
      ];
}
