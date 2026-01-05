<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketReview extends Model
{
    protected $connection = 'mysql';
    protected $table = 'tickets_reviews';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'ticket_id',
        'user_id',
        'executor_id',
        'rating',
        'comment',
    ];
     protected static function booted()
    {
        static::creating(function ($model) {
            $model->id ??= (string) Str::uuid();
        });
    }

    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id');
    }
}
