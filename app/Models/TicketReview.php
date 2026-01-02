<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReview extends Model
{
    protected $connection = 'mysql';
    protected $table = 'tickets_reviews';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'executor_id',
        'rating',
        'comment',
    ];

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
