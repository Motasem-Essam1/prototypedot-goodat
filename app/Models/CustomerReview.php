<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    use HasFactory;
    protected $table = 'customer_reviews';
    protected $fillable = [
        'description',
        'rate',
    ];
    public $timestamps = true;

    public function user()
    {
        return $this -> belongsTo(User::class,'review_id','id');
    }

    public function Task()
    {
        return $this -> belongsTo(Task::class,'review_id','id');
    }

    public function Service()
    {
        return $this -> belongsTo(Services::class,'review_id','id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
