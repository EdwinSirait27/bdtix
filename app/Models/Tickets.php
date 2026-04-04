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
        'queue_date',
        'title',
        'category',
        'remark',
        'sub_category',
        'description',
        'status',
        'attachment_folder',
        'attachment_url',
        'executor_id',
        'last_overdue_reminder_at',
        'estimation_to',
        'priority',
        'progressed_at',
        'notes_executor',
        'finished',
        'estimation',
        'duration_type',
        'duration_value',
    ];
    protected $casts = [
    'estimation' => 'datetime',
    'estimation_to' => 'datetime',
    'progressed_at' => 'datetime',
    'finished'   => 'datetime',
    'last_overdue_reminder_at'   => 'datetime',
    'created_at'   => 'datetime',
    'queue_date'   => 'date',
];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id ??= (string) Str::uuid();
        });
    }
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id', 'id');
    }
    public function attachments()
    {
        return $this->hasMany(Ticketattachments::class, 'ticket_id', 'id');
    }
    public function executorAttachments()
    {
        return $this->hasMany(TicketExecutorAttachment::class, 'ticket_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function review()
{
    return $this->hasOne(TicketReview::class, 'ticket_id', 'id');
}

}
