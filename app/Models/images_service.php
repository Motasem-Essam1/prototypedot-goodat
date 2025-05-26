<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class images_service extends Model
{
    use HasFactory;
    protected $fillable = [
        'images_id',
        'services_id'
      ];
}
