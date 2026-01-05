<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Tickets extends Model
{
    protected $table = 'ticket_tables';
    public $incrementing = false;
    protected $connection = 'mysql';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'request_uuid',
        'user_id',
        'queue_number',
        'title',
        'category',
        'description',
        'status',
        'attachment_folder',
        'attachment_url',
        'executor_id',
        'priority',
        'notes_executor',
        'finished',
        'estimation',
    ];
    protected $casts = [
    'estimation' => 'datetime',
    'finished'   => 'datetime',
    'created_at'   => 'datetime',
];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id ??= (string) Str::uuid();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id');
    }
    public function attachments()
    {
        return $this->hasMany(Ticketattachments::class, 'ticket_id', 'id');
    }
    public function review()
{
    return $this->hasOne(TicketReview::class, 'ticket_id', 'id');
}

}
