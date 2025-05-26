<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderContact extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'visitor_id',
        'visitor_type',
        'item_id',
        'item_type',
    ];

    public function provider() {
        return $this -> belongsTo(User::class,'user_id','id');
    }

    public function visitor() {
        return $this -> belongsTo(User::class,'visitor_id','id');
    }
}
