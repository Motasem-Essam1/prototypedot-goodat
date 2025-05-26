<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_to_notify',
        'message',
        'item_id',
        'item_type',
        'action_type',
        'is_read'
    ];

    protected $appends = ['item'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userToNotify(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_to_notify');
    }

    public function getItemAttribute(){
        if ($this['item_type'] == 'service') {
            return Services::where('id', $this['item_id'])->get()->first();
        }
        if ($this['item_type'] == 'task') {
            return Task::where('id', $this['item_id'])->get()->first();
        }
        if ($this['item_type'] == 'provider') {
            return User::where('id', $this['item_id'])->get()->first();
        }
    }

}
