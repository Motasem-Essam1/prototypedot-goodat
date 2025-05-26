<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'favorite_id',
        'item_type'
    ];

    protected $appends = ['item'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getItemAttribute(){
        if ($this['item_type'] == 'service') {
            return Services::find($this['favorite_id']);
        }
        if ($this['item_type'] == 'task') {
            return Task::find($this['favorite_id']);
        }
        if ($this['item_type'] == 'provider') {
            return User::find($this['favorite_id']);
        }
    }

//    public function favorite() {
//        if ($this->item_type == 'service') {
//            return $this->belongsTo(Services::class, 'favorite_id');
//        }
//
//        if ($this->item_type == 'task') {
//            return $this->belongsTo(Task::class, 'favorite_id');
//        }
//
//        if ($this->item_type == 'provider') {
//            return $this->belongsTo(User::class, 'favorite_id');
//        }
//    }
}
